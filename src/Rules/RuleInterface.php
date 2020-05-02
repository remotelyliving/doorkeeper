<?php

declare(strict_types=1);

namespace RemotelyLiving\Doorkeeper\Rules;

use RemotelyLiving\Doorkeeper\Requestor;

interface RuleInterface extends \JsonSerializable
{
    /**
     * @return \RemotelyLiving\Doorkeeper\Rules\RuleInterface[]
     */
    public function getPrerequisites(): iterable;

    public function hasPrerequisites(): bool;

    public function addPrerequisite(RuleInterface $rule): void;

    public function canBeSatisfied(Requestor $requestor = null): bool;

    /**
     * @return mixed|null
     */
    public function getValue();
}
