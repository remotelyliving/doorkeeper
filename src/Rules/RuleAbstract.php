<?php
namespace RemotelyLiving\Doorkeeper\Rules;

use RemotelyLiving\Doorkeeper\Requestor;

abstract class RuleAbstract implements RuleInterface
{
    /**
     * @var \RemotelyLiving\Doorkeeper\Rules\RuleInterface
     */
    private $prerequisite;

    /**
     * @param \RemotelyLiving\Doorkeeper\Rules\RuleInterface $rule
     *
     * @throws \DomainException
     */
    final public function setPrerequisite(RuleInterface $rule): void
    {
        if ($this->prerequisite) {
            throw new \DomainException('Prerequisite rule already set');
        }

        $this->prerequisite = $rule;
    }

    /**
     * @return bool
     */
    final public function hasPrerequisite(): bool
    {
        return (bool) $this->prerequisite;
    }

    final public function canBeSatisfied(Requestor $requestor = null): bool
    {
        if ($this->hasPrerequisite() && !$this->prerequisite->canBeSatisfied($requestor)) {
            return false;
        }

        return $this->childCanBeSatisfied($requestor);
    }

    /**
     * @inheritdoc
     */
    abstract protected function childCanBeSatisfied(Requestor $requestor = null): bool;

    /**
     * @param \RemotelyLiving\Doorkeeper\Requestor|null $requestor
     * @param string                                    $identity_name
     *
     * @return bool
     */
    final protected function requestorHasIdentity(Requestor $requestor = null, string $identity_name): bool
    {
        return ($requestor && $requestor->getIdentifiationByClassName($identity_name));
    }
}
