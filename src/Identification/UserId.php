<?php

declare(strict_types=1);

namespace RemotelyLiving\Doorkeeper\Identification;

use Ramsey\Uuid;

final class UserId extends AbstractIdentification
{
    protected function validate($value): void
    {
        if (is_int($value)) {
            return;
        }

        if (Uuid\Uuid::isValid((string) $value)) {
            return;
        }

        if (is_string($value)) {
            return;
        }

        throw new \InvalidArgumentException("{$value} is not an integer or uuid");
    }
}
