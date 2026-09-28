<?php

declare(strict_types=1);

namespace App\Commissioning\Tests;

use PHPUnit\Framework\TestCase;

final class CommissioningSmokeTest extends TestCase
{
    public function testFoundationExists(): void
    {
        self::assertFileExists(dirname(__DIR__).'/src/CommissioningBundle.php');
    }
}
