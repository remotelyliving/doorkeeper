<?php

declare(strict_types=1);

namespace RemotelyLiving\Doorkeeper\Identification;

final class Environment extends AbstractIdentification
{
    protected function validate($string): void
    {
        if (!is_string($string)) {
            throw new \InvalidArgumentException("{$string} is not a string");
        }
    }
}
