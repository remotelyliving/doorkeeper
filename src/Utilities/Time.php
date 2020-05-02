<?php

declare(strict_types=1);

namespace RemotelyLiving\Doorkeeper\Utilities;

class Time
{
    public function getImmutableDateTime(string $timeString = 'now'): \DateTimeImmutable
    {
        return new \DateTimeImmutable($timeString);
    }
}
