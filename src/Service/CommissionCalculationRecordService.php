<?php

declare(strict_types=1);

namespace App\Commissioning\Service;

use App\Commissioning\DTO\CommissionRecordCalculationRequestDTO;
use App\Commissioning\DTO\CommissionRecordCalculationResultDTO;
use App\Commissioning\Entity\CommissionCalculationEntity;
use App\Commissioning\Entity\CommissionCalculationLineEntity;
use App\Commissioning\Entity\CommissionLedgerEntryEntity;
use App\Commissioning\Entity\CommissionPlanEntity;
use App\Commissioning\Enum\CommissionCalculationLineTypeEnum;
use App\Commissioning\RepositoryInterface\CommissionCalculationLineRepositoryInterface;
use App\Commissioning\RepositoryInterface\CommissionCalculationRepositoryInterface;
use App\Commissioning\RepositoryInterface\CommissionLedgerEntryRepositoryInterface;
use App\Commissioning\RepositoryInterface\CommissionPlanRepositoryInterface;
use App\Commissioning\RepositoryInterface\CommissionTransactionRepositoryInterface;
use App\Commissioning\ServiceInterface\CommissionCalculationRecordServiceInterface;
use App\Commissioning\ServiceInterface\CommissionIdempotencyServiceInterface;

/**
 * Coordinates Commissioning application behavior implemented by CommissionCalculationRecordService across typed collaborators and boundaries.
 */
final class CommissionCalculationRecordService implements CommissionCalculationRecordServiceInterface
{
    public function __construct(
        private readonly CommissionPlanRepositoryInterface $planRepository,
        private readonly CommissionCalculationRepositoryInterface $calculationRepository,
        private readonly CommissionCalculationLineRepositoryInterface $lineRepository,
        private readonly CommissionLedgerEntryRepositoryInterface $ledgerRepository,
        private readonly CommissionIdempotencyServiceInterface $idempotencyService,
        private readonly CommissionTransactionRepositoryInterface $transactionRepository,
    ) {
    }

    /**
     * Performs the record operation defined by this typed Commissioning application contract.
     */
    public function record(CommissionRecordCalculationRequestDTO $request): CommissionRecordCalculationResultDTO
    {
        $idempotency = $this->idempotencyService->checkEconomicEvent($request->economicEventReference);

        if ($idempotency->duplicate) {
            return $this->duplicateResult($request);
        }

        return $this->transactionRepository->transactional(
            fn (): CommissionRecordCalculationResultDTO => $this->recordNewCalculation($request),
        );
    }

    private function duplicateResult(CommissionRecordCalculationRequestDTO $request): CommissionRecordCalculationResultDTO
    {
        $existing = $this->calculationRepository->findOneByEconomicEventReference($request->economicEventReference);

        return new CommissionRecordCalculationResultDTO(
            economicEventReference: $request->economicEventReference,
            currencyCode: $request->currencyCode,
            commissionMinorAmount: $existing?->getCommissionMinorAmount() ?? $request->commissionMinorAmount,
            ledgerStatus: 'duplicate',
            duplicate: true,
        );
    }

    private function recordNewCalculation(CommissionRecordCalculationRequestDTO $request): CommissionRecordCalculationResultDTO
    {
        $plan = $this->resolvePlan($request);
        $calculation = new CommissionCalculationEntity(
            plan: $plan,
            economicEventReference: $request->economicEventReference,
            currencyCode: $request->currencyCode,
            basisMinorAmount: $request->basisMinorAmount,
            commissionMinorAmount: $request->commissionMinorAmount,
        );

        $this->calculationRepository->save($calculation);
        $this->persistLines($calculation, $request);

        $ledgerEntry = new CommissionLedgerEntryEntity(
            calculation: $calculation,
            beneficiaryReference: $request->beneficiaryReference,
            currencyCode: $request->currencyCode,
            minorAmount: $request->commissionMinorAmount,
        );
        $this->ledgerRepository->save($ledgerEntry);

        return new CommissionRecordCalculationResultDTO(
            economicEventReference: $request->economicEventReference,
            currencyCode: $request->currencyCode,
            commissionMinorAmount: $request->commissionMinorAmount,
            ledgerStatus: $ledgerEntry->getStatus()->value,
            duplicate: false,
        );
    }

    private function resolvePlan(CommissionRecordCalculationRequestDTO $request): CommissionPlanEntity
    {
        $plan = $this->planRepository->findOneByCode($request->planCode);
        if ($plan instanceof CommissionPlanEntity) {
            return $plan;
        }

        $plan = new CommissionPlanEntity($request->planCode, $request->planName);
        $this->planRepository->save($plan);

        return $plan;
    }

    private function persistLines(
        CommissionCalculationEntity $calculation,
        CommissionRecordCalculationRequestDTO $request,
    ): void {
        foreach ($request->lines as $line) {
            $this->lineRepository->save(new CommissionCalculationLineEntity(
                calculation: $calculation,
                lineType: CommissionCalculationLineTypeEnum::from($line->lineType),
                currencyCode: $line->currencyCode,
                minorAmount: $line->minorAmount,
                explanation: $line->explanation,
            ));
        }
    }
}
