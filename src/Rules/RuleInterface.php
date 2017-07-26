<?php

namespace RemotelyLiving\Doorkeeper\Rules;

use RemotelyLiving\Doorkeeper\Requestor;

interface RuleInterface
{
    /**
     * @return RuleInterface[]
     */
    public function getPrerequisites();

    /**
     * @return bool
     */
    public function hasPrerequisites(): bool;

    public function addPrerequisite(RuleInterface $rule);

    /**
     * @param \RemotelyLiving\Doorkeeper\Requestor|null $requestor
     *
     * @return bool
     */
    public function canBeSatisfied(Requestor $requestor = null): bool;

    /**
     * @return mixed|null
     */
    public function getValue();
}
