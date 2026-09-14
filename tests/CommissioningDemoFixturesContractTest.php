<?php

declare(strict_types=1);

namespace Tests;

use App\Commissioning\DataFixtures\CommissioningDemoFixtures;
use App\Commissioning\Entity\CommissionAttributionEntity;
use App\Commissioning\Entity\CommissionBeneficiaryEntity;
use App\Commissioning\Entity\CommissionCalculationEntity;
use App\Commissioning\Entity\CommissionCalculationLineEntity;
use App\Commissioning\Entity\CommissionLedgerEntryEntity;
use App\Commissioning\Entity\CommissionPlanEntity;
use App\Commissioning\Entity\CommissionRateEntity;
use App\Commissioning\Entity\CommissionSettlementBatchEntity;
use App\Commissioning\Entity\CommissionSettlementBatchEntryEntity;
use Doctrine\DBAL\DriverManager;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Mapping\UnderscoreNamingStrategy;
use Doctrine\ORM\ORMSetup;
use Doctrine\ORM\Tools\SchemaTool;
use PHPUnit\Framework\TestCase;

final class CommissioningDemoFixturesContractTest extends TestCase
{
    public function testDemoFixturesPersistIntegerPrimaryKeys(): void
    {
        $entityManager = $this->entityManager();
        (new CommissioningDemoFixtures())->load($entityManager);

        $plans = $entityManager->createQuery('SELECT plan FROM '.CommissionPlanEntity::class.' plan ORDER BY plan.id ASC')->getResult();
        $beneficiaries = $entityManager->createQuery('SELECT beneficiary FROM '.CommissionBeneficiaryEntity::class.' beneficiary ORDER BY beneficiary.id ASC')->getResult();
        $rates = $entityManager->createQuery('SELECT rate FROM '.CommissionRateEntity::class.' rate ORDER BY rate.id ASC')->getResult();
        $batches = $entityManager->createQuery('SELECT batch FROM '.CommissionSettlementBatchEntity::class.' batch ORDER BY batch.id ASC')->getResult();
        $calculations = $entityManager->createQuery('SELECT calculation FROM '.CommissionCalculationEntity::class.' calculation ORDER BY calculation.id ASC')->getResult();
        $lines = $entityManager->createQuery('SELECT line FROM '.CommissionCalculationLineEntity::class.' line ORDER BY line.id ASC')->getResult();
        $ledgerEntries = $entityManager->createQuery('SELECT ledgerEntry FROM '.CommissionLedgerEntryEntity::class.' ledgerEntry ORDER BY ledgerEntry.id ASC')->getResult();
        $batchEntries = $entityManager->createQuery('SELECT batchEntry FROM '.CommissionSettlementBatchEntryEntity::class.' batchEntry ORDER BY batchEntry.id ASC')->getResult();
        $attributions = $entityManager->createQuery('SELECT attribution FROM '.CommissionAttributionEntity::class.' attribution ORDER BY attribution.id ASC')->getResult();

        self::assertCount(3, $plans);
        self::assertCount(6, $beneficiaries);
        self::assertCount(12, $rates);
        self::assertCount(3, $batches);
        self::assertCount(3, $calculations);
        self::assertCount(6, $lines);
        self::assertCount(3, $ledgerEntries);
        self::assertCount(3, $batchEntries);
        self::assertCount(3, $attributions);

        $this->assertIntegerIdentifiers($entityManager, $plans);
        $this->assertIntegerIdentifiers($entityManager, $beneficiaries);
        $this->assertIntegerIdentifiers($entityManager, $rates);
        $this->assertIntegerIdentifiers($entityManager, $batches);
        $this->assertIntegerIdentifiers($entityManager, $calculations);
        $this->assertIntegerIdentifiers($entityManager, $lines);
        $this->assertIntegerIdentifiers($entityManager, $ledgerEntries);
        $this->assertIntegerIdentifiers($entityManager, $batchEntries);
        $this->assertIntegerIdentifiers($entityManager, $attributions);
    }

    private function entityManager(): EntityManager
    {
        $projectDir = dirname(__DIR__);
        $config = ORMSetup::createAttributeMetadataConfig([$projectDir.'/src/Entity'], true);
        $config->setNamingStrategy(new UnderscoreNamingStrategy());
        $config->enableNativeLazyObjects(true);
        $connection = DriverManager::getConnection([
            'driver' => 'pdo_sqlite',
            'memory' => true,
        ]);

        $entityManager = new EntityManager($connection, $config);
        $schemaTool = new SchemaTool($entityManager);
        $schemaTool->createSchema($entityManager->getMetadataFactory()->getAllMetadata());

        return $entityManager;
    }

    /**
     * @param list<object> $entities
     */
    private function assertIntegerIdentifiers(EntityManager $entityManager, array $entities): void
    {
        foreach ($entities as $entity) {
            $id = $entityManager->getUnitOfWork()->getSingleIdentifierValue($entity);

            self::assertIsInt($id);
            self::assertGreaterThan(0, $id);
        }
    }
}
