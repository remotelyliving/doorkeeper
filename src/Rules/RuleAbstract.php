<?php
namespace RemotelyLiving\Doorkeeper\Rules;

use RemotelyLiving\Doorkeeper\Identification\IdentificationAbstract;
use RemotelyLiving\Doorkeeper\Requestor;

abstract class RuleAbstract implements RuleInterface
{
    /**
     * @var \RemotelyLiving\Doorkeeper\Rules\RuleInterface|null
     */
    private $prerequisite = null;

    /**
     * @param \RemotelyLiving\Doorkeeper\Rules\RuleInterface $rule
     */
    final public function setPrerequisite(RuleInterface $rule)
    {
        if ($this->prerequisite) {
            throw new \DomainException('Prerequisite rule already set');
        }

        $this->prerequisite = $rule;
    }

    /**
     * @inheritdoc
     */
    final public function hasPrerequisite(): bool
    {
        return (bool) $this->prerequisite;
    }

    /**
     * @inheritdoc
     */
    final public function getPrerequisite()
    {
        return $this->prerequisite;
    }

    /**
     * @inheritdoc
     */
    public function getValue()
    {
        return null;
    }

    /**
     * @param Requestor|null $requestor
     *
     * @return bool
     */
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
     * @param Requestor $requestor
     * @param IdentificationAbstract $identification
     *
     * @return bool
     */
    protected function requestorHasMatchingId(Requestor $requestor = null, IdentificationAbstract $identification): bool
    {
        if (!$requestor) {
            return false;
        }

        return $requestor->hasIdentification($identification);
    }
}
