<?php
namespace RemotelyLiving\Doorkeeper\Utilities;

class Time
{
    public function getImmutableDateTime(string $time_string = 'now'): \DateTimeImmutable
    {
        return new \DateTimeImmutable($time_string);
    }
}
