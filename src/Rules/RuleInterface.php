<?php

namespace RemotelyLiving\Doorkeeper\Rules;

use RemotelyLiving\Doorkeeper\Requestor;

interface RuleInterface
{
    /**
     * @return RuleInterface|null
     */
    public function getPrerequisite(): ?RuleInterface;

    /**
     * @return bool
     */
    public function hasPrerequisite(): bool;
    
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
