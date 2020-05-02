<?php

declare(strict_types=1);

namespace RemotelyLiving\Doorkeeper;

interface RequestorInterface
{
    public function getIdentityHash(): string;

    public function registerIdentification(Identification\IdentificationInterface $identification): void;

    public function getIdentificationCollections(): array;

    public function hasIdentification(Identification\IdentificationInterface $identification): bool;
}
