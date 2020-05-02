<?php

declare(strict_types=1);

namespace RemotelyLiving\Doorkeeper\Features;

final class Set implements \JsonSerializable
{
    /**
     * @var \RemotelyLiving\Doorkeeper\Features\Feature[]
     */
    private $features = [];

    /**
     * @param \RemotelyLiving\Doorkeeper\Features\Feature[] $features
     */
    public function __construct(array $features = [])
    {
        foreach ($features as $feature) {
            $this->pushFeature($feature);
        }
    }

    public function pushFeature(Feature $feature): void
    {
        $this->features[$feature->getName()] = $feature;
    }

    public function offsetExists(string $featureName): bool
    {
        return isset($this->features[$featureName]);
    }

    /**
     * @return \RemotelyLiving\Doorkeeper\Features\Feature[]
     */
    public function getFeatures(): array
    {
        return $this->features;
    }

    public function getFeatureByName(string $featureName): Feature
    {
        if (!$this->offsetExists($featureName)) {
            throw new \OutOfBoundsException("Feature {$featureName} does not exist");
        }

        return $this->features[$featureName];
    }

    /**
     * @return array
     */
    public function jsonSerialize(): array
    {
        return ['features' => $this->features];
    }
}
