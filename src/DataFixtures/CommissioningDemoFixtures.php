<?php

declare(strict_types=1);

namespace App\Commissioning\DataFixtures;

use App\Commissioning\Entity\CommissionAttributionEntity;
use App\Commissioning\Entity\CommissionBeneficiaryEntity;
use App\Commissioning\Entity\CommissionCalculationEntity;
use App\Commissioning\Entity\CommissionCalculationLineEntity;
use App\Commissioning\Entity\CommissionLedgerEntryEntity;
use App\Commissioning\Entity\CommissionPlanEntity;
use App\Commissioning\Entity\CommissionRateEntity;
use App\Commissioning\Entity\CommissionSettlementBatchEntity;
use App\Commissioning\Entity\CommissionSettlementBatchEntryEntity;
use App\Commissioning\Enum\CommissionAttributionSourceTypeEnum;
use App\Commissioning\Enum\CommissionBeneficiaryTypeEnum;
use App\Commissioning\Enum\CommissionCalculationLineTypeEnum;
use App\Commissioning\Enum\CommissionRateTypeEnum;
use Doctrine\Persistence\ObjectManager;

final class CommissioningDemoFixtures
{
    public function load(ObjectManager $manager): void
    {
        $plans = [];
        $beneficiaries = [];

        foreach (range(1, 3) as $index) {
            $plan = new CommissionPlanEntity(
                sprintf('plan-%02d', $index),
                sprintf('Demo Company %02d Commission Plan', $index),
            );
            $manager->persist($plan);
            $plans[] = $plan;

            foreach ([CommissionBeneficiaryTypeEnum::Partner, CommissionBeneficiaryTypeEnum::Affiliate] as $type) {
                $beneficiary = new CommissionBeneficiaryEntity(
                    $type,
                    sprintf('%s-%02d', $type->value, $index),
                    sprintf('Demo Company %02d %s', $index, $type->value),
                );
                $manager->persist($beneficiary);
                $beneficiaries[] = $beneficiary;
            }

            foreach (CommissionRateTypeEnum::cases() as $rateType) {
                $manager->persist(new CommissionRateEntity(
                    $plan,
                    $rateType,
                    CommissionRateTypeEnum::Percentage === $rateType ? '0.1200' : (CommissionRateTypeEnum::Hybrid === $rateType ? '0.0750' : null),
                    CommissionRateTypeEnum::Fixed === $rateType ? 750 : (CommissionRateTypeEnum::Hybrid === $rateType ? 500 : null),
                    CommissionRateTypeEnum::Fixed === $rateType ? 'USD' : (CommissionRateTypeEnum::Hybrid === $rateType ? 'USD' : null),
                ));
            }

            $batch = new CommissionSettlementBatchEntity(sprintf('batch-%02d', $index));
            $manager->persist($batch);

            $calculation = new CommissionCalculationEntity(
                $plan,
                sprintf('event-EVT-%05d-%02d', 10000 + $index, $index),
                ['USD', 'EUR', 'UAH'][($index - 1) % 3],
                10000 + ($index * 3500),
                1200 + ($index * 275),
            );
            $manager->persist($calculation);

            $manager->persist(new CommissionCalculationLineEntity(
                $calculation,
                CommissionCalculationLineTypeEnum::Base,
                $calculation->getCurrencyCode(),
                $calculation->getBasisMinorAmount(),
                'Basis amount',
            ));
            $manager->persist(new CommissionCalculationLineEntity(
                $calculation,
                CommissionCalculationLineTypeEnum::PercentageCommission,
                $calculation->getCurrencyCode(),
                $calculation->getCommissionMinorAmount(),
                'Percentage commission',
            ));

            $ledgerEntry = new CommissionLedgerEntryEntity(
                $calculation,
                $beneficiaries[$index - 1]->getBeneficiaryReference(),
                $calculation->getCurrencyCode(),
                $calculation->getCommissionMinorAmount(),
            );
            $manager->persist($ledgerEntry);
            $manager->persist(new CommissionSettlementBatchEntryEntity($batch, $ledgerEntry));

            $manager->persist(new CommissionAttributionEntity(
                CommissionAttributionSourceTypeEnum::cases()[($index - 1) % count(CommissionAttributionSourceTypeEnum::cases())],
                sprintf('source-%02d', $index),
                sprintf('economic-event-%02d', $index),
            ));
        }

        $manager->flush();
    }
}
