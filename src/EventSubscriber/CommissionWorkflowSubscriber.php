<?php

declare(strict_types=1);

namespace App\Commissioning\EventSubscriber;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 * Subscribes CommissionWorkflowSubscriber to Commissioning workflow events while keeping framework integration explicit.
 */
final class CommissionWorkflowSubscriber implements EventSubscriberInterface
{
    /**
     * @return array<string, string>
     */
    public static function getSubscribedEvents(): array
    {
        return [];
    }
}
