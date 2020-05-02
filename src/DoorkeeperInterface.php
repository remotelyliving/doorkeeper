<?php

declare(strict_types=1);

namespace RemotelyLiving\Doorkeeper;

interface DoorkeeperInterface
{
    public function grantsAccessTo(string $featureName): bool;

    public function grantsAccessToRequestor(string $featureName, RequestorInterface $requestor = null): bool;
}
