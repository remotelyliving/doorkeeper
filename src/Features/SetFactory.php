<?php

declare(strict_types=1);

namespace RemotelyLiving\Doorkeeper\Features;

final class SetFactory
{
    private Factory $featureFactory;

    public function __construct(Factory $factory = null)
    {
        $this->featureFactory = $factory ?? new Factory();
    }

    public function createFromArray(array $featureSet): Set
    {
        $features = [];

        foreach ($featureSet as $feature) {
            $features[] = $this->featureFactory->createFromArray($feature);
        }

        return new Set($features);
    }
}
