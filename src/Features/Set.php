<?php
namespace RemotelyLiving\Doorkeeper\Features;

class Set
{
    /**
     * @var \RemotelyLiving\Doorkeeper\Features\Feature[]
     */
    private $features;

    /**
     * @param \RemotelyLiving\Doorkeeper\Features\Feature[] $features
     */
    public function __construct(array $features = [])
    {
        foreach ($features as $feature) {
            $this->pushFeature($feature);
        }
    }

    /**
     * @param \RemotelyLiving\Doorkeeper\Features\Feature $feature
     */
    public function pushFeature(Feature $feature): void
    {
        $this->features[$feature->getName()] = $feature;
    }

    /**
     * @param string $feature_name
     *
     * @return bool
     */
    public function offsetExists(string $feature_name): bool
    {
        return isset($this->features[$feature_name]);
    }

    /**
     * @return \RemotelyLiving\Doorkeeper\Features\Feature[]
     */
    public function getFeatures(): array
    {
        return $this->features;
    }

    /**
     * @param string $feature_name
     *
     * @return \RemotelyLiving\Doorkeeper\Features\Feature
     */
    public function getFeatureByName(string $feature_name): Feature
    {
        if (!$this->offsetExists($feature_name)) {
            throw new \OutOfBoundsException("Feature {$feature_name} does not exist");
        }

        return $this->features[$feature_name];
    }
}
