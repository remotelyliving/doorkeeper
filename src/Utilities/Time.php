<?php
namespace RemotelyLiving\Doorkeeper\Utilities;

class Time
{
    /**
     * @param string $time_string
     *
     * @return \DateTimeImmutable
     */
    public function getImmutableDateTime(string $time_string = 'now'): \DateTimeImmutable
    {
        return new \DateTimeImmutable($time_string);
    }
}
