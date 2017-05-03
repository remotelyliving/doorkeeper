<?php
namespace RemotelyLiving\Doorkeeper\Features;

use RemotelyLiving\Doorkeeper\Rules;

class Factory
{
    /**
     * @var \RemotelyLiving\Doorkeeper\Rules\Factory
     */
    private $rule_factory;

    /**
     * @param \RemotelyLiving\Doorkeeper\Rules\Factory|null $rules_factory
     */
    public function __construct(Rules\Factory $rules_factory = null)
    {
        $this->rule_factory = $rules_factory ?? new Rules\Factory();
    }

    /**
     * @param array $feature
     *
     * @return \RemotelyLiving\Doorkeeper\Features\Feature
     */
    public function createFromArray(array $feature): Feature
    {
        $rules = [];

        foreach ($feature['rules'] as $rule) {
            $rules[] = $this->rule_factory->createFromArray($rule);
        }

        return new Feature($feature['id'], $feature['enabled'], $rules);
    }
}
