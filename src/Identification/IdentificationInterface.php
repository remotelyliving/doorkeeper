<?php

declare(strict_types=1);

namespace RemotelyLiving\Doorkeeper\Identification;

interface IdentificationInterface
{
    /**
     * @return mixed
     */
    public function getIdentifier();

    public function getType(): string;

    public function equals(IdentificationInterface $identity): bool;
}
