<?php
namespace RemotelyLiving\Doorkeeper\Features;

class SetFactory
{
    /**
     * @var \RemotelyLiving\Doorkeeper\Features\Factory
     */
    private $feature_factory;

    /**
     * SetFactory constructor.
     *
     * @param \RemotelyLiving\Doorkeeper\Features\Factory|null $factory
     */
    public function __construct(Factory $factory = null)
    {
        $this->feature_factory = $factory ?? new Factory();
    }

    /**
     * @param array $feature_set
     *
     * @return \RemotelyLiving\Doorkeeper\Features\Set
     */
    public function createFromArray(array $feature_set): Set
    {
        $features = [];

        foreach ($feature_set as $feature) {
            $features[] = $this->feature_factory->createFromArray($feature);
        }

        return new Set($features);
    }
}
