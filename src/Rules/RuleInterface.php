<?php

namespace RemotelyLiving\Doorkeeper\Rules;

use RemotelyLiving\Doorkeeper\Requestor;

interface RuleInterface
{
    /**
     * @param \RemotelyLiving\Doorkeeper\Requestor|null $requestor
     *
     * @return bool
     */
    public function canBeSatisfied(Requestor $requestor = null): bool;
}
