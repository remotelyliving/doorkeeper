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
        $this->features[$feature->getId()] = $feature;
    }

    /**
     * @param string $feature_id
     *
     * @return bool
     */
    public function offsetExists(string $feature_id): bool
    {
        return isset($this->features[$feature_id]);
    }

    /**
     * @return \RemotelyLiving\Doorkeeper\Features\Feature[]
     */
    public function getFeatures(): array
    {
        return $this->features;
    }

    /**
     * @param string $feature_id
     *
     * @return \RemotelyLiving\Doorkeeper\Features\Feature
     */
    public function getFeatureById(string $feature_id): Feature
    {
        if (!$this->offsetExists($feature_id)) {
            throw new \OutOfBoundsException("Feature {$feature_id} does not exist");
        }

        return $this->features[$feature_id];
    }
}
