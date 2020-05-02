<?php

declare(strict_types=1);

namespace RemotelyLiving\Doorkeeper\Identification;

final class IntegerId extends AbstractIdentification
{
    protected function validate($id): void
    {
        if (!is_int($id) || $id < 0) {
            throw new \InvalidArgumentException("{$id} is not a positive integer id");
        }
    }
}
