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

    /**
     * @inheritdoc
     */
    final public function hasPrerequisites(): bool
    {
        return (bool) $this->prerequisites;
    }

    /**
     * @inheritdoc
     */
    final public function getPrerequisites()
    {
        return $this->prerequisites;
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
        if ($this->hasPrerequisites()) {
            foreach ($this->prerequisites as $prerequisite) {
                if (!$prerequisite->canBeSatisfied($requestor)) {
                    return false;
                }
            }
        }

        return $this->childCanBeSatisfied($requestor);
    }

    /**
     * @return array
     */
    public function jsonSerialize(): array
    {
        return [
            'type' => static::class,
            'value' => $this->getValue(),
            'prerequisites' => $this->prerequisites,
        ];
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
