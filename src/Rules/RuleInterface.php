<?php

namespace RemotelyLiving\Doorkeeper\Rules;

use RemotelyLiving\Doorkeeper\Requestor;

interface RuleInterface
{
    /**
     * @return string
     */
    public function getFeatureId(): string;

    /**
     * @param \RemotelyLiving\Doorkeeper\Requestor|null $requestor
     *
     * @return bool
     */
    public function canBeSatisfied(Requestor $requestor = null): bool;
}
