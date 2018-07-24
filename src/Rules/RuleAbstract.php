<?php
namespace RemotelyLiving\Doorkeeper\Rules;

use RemotelyLiving\Doorkeeper\Identification\IdentificationAbstract;
use RemotelyLiving\Doorkeeper\Requestor;

abstract class RuleAbstract implements RuleInterface
{
    /**
     * @var \RemotelyLiving\Doorkeeper\Rules\RuleInterface[]
     */
    private $prerequisites = [];

    /**
     * @param \RemotelyLiving\Doorkeeper\Rules\RuleInterface $rule
     */
    final public function addPrerequisite(RuleInterface $rule)
    {
        $this->prerequisites[] = $rule;
    }

    final public function hasPrerequisites(): bool
    {
        return (bool) $this->prerequisites;
    }

    final public function getPrerequisites()
    {
        return $this->prerequisites;
    }

    public function getValue()
    {
        return null;
    }

    final public function canBeSatisfied(Requestor $requestor = null): bool
    {
        if ($this->hasPrerequisites()) {
            foreach ($this->prerequisites as $prerequisite) {
                if (!$prerequisite->canBeSatisfied($requestor)) {
                    return false;
                }
            }
        }

        return $this->childCanBeSatisfied($requestor);
    }

    public function jsonSerialize(): array
    {
        return [
            'type' => static::class,
            'value' => $this->getValue(),
            'prerequisites' => $this->prerequisites,
        ];
    }

    abstract protected function childCanBeSatisfied(Requestor $requestor = null): bool;

    protected function requestorHasMatchingId(Requestor $requestor = null, IdentificationAbstract $identification): bool
    {
        if (!$requestor) {
            return false;
        }

        return $requestor->hasIdentification($identification);
    }
}
