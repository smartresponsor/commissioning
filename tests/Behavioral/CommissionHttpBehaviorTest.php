<?php

declare(strict_types=1);

namespace App\Commissioning\Tests\Behavioral;

use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

final class CommissionHttpBehaviorTest extends KernelTestCase
{
    private function client(): KernelBrowser
    {
        $kernel = static::bootKernel(['environment' => 'test']);

        return new KernelBrowser($kernel);
    }

    public function testCalculationPreviewRejectsMalformedJson(): void
    {
        $client = $this->client();
        $client->request(
            'POST',
            '/api/commissioning/calculation/preview',
            server: ['CONTENT_TYPE' => 'application/json'],
            content: '{invalid-json',
        );

        self::assertSame(422, $client->getResponse()->getStatusCode());
        self::assertSame('application/json', $client->getResponse()->headers->get('content-type'));
    }

    public function testSettlementBatchRejectsMalformedJson(): void
    {
        $client = $this->client();
        $client->request(
            'POST',
            '/api/commissioning/settlement/batch',
            server: ['CONTENT_TYPE' => 'application/json'],
            content: '{invalid-json',
        );

        self::assertSame(422, $client->getResponse()->getStatusCode());
        self::assertSame('application/json', $client->getResponse()->headers->get('content-type'));
    }

    public function testCalculationRecordRejectsGetMethod(): void
    {
        $client = $this->client();
        $client->request('GET', '/api/commissioning/calculation');

        self::assertSame(405, $client->getResponse()->getStatusCode());
    }

    public function testSettlementReadyRejectsGetMethod(): void
    {
        $client = $this->client();
        $client->request('GET', '/api/commissioning/ledger/settlement/ready');

        self::assertSame(405, $client->getResponse()->getStatusCode());
    }
}
