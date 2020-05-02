<?php

declare(strict_types=1);

namespace RemotelyLiving\Doorkeeper\Features;

interface SetProviderInterface
{
    public function getFeatureSet(): Set;
}
