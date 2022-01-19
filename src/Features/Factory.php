<?php

declare(strict_types=1);

namespace RemotelyLiving\Doorkeeper\Features;

use RemotelyLiving\Doorkeeper\Rules;

final class Factory
{
    private Rules\Factory $ruleFactory;

    public function __construct(Rules\Factory $rulesFactory = null)
    {
        $this->ruleFactory = $rulesFactory ?? new Rules\Factory();
    }

    public function createFromArray(array $feature): Feature
    {
        $rules = [];

        if (isset($feature['rules'])) {
            foreach ($feature['rules'] as $rule) {
                $rules[] = $this->ruleFactory->createFromArray($rule);
            }
        }

        return new Feature($feature['name'], $feature['enabled'], $rules);
    }
}
