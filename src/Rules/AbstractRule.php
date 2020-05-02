<?php

declare(strict_types=1);

namespace RemotelyLiving\Doorkeeper\Rules;

use RemotelyLiving\Doorkeeper\Identification;
use RemotelyLiving\Doorkeeper\Requestor;

abstract class AbstractRule implements RuleInterface
{
    /**
     * @var \RemotelyLiving\Doorkeeper\Rules\RuleInterface[]
     */
    private $prerequisites = [];

    final public function addPrerequisite(RuleInterface $rule): void
    {
        $this->prerequisites[] = $rule;
    }

    final public function hasPrerequisites(): bool
    {
        return (bool) $this->prerequisites;
    }

    final public function getPrerequisites(): iterable
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

    protected function requestorHasMatchingId(
        Requestor $requestor = null,
        Identification\IdentificationInterface $identification
    ): bool {
        if (!$requestor) {
            return false;
        }

        return $requestor->hasIdentification($identification);
    }

    abstract protected function childCanBeSatisfied(Requestor $requestor = null): bool;
}
