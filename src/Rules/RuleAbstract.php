<?php
namespace RemotelyLiving\Doorkeeper\Rules;

use RemotelyLiving\Doorkeeper\Requestor;

abstract class RuleAbstract implements RuleInterface
{
    /**
     * @var string
     */
    private $feature_id;

    /**
     * @var \RemotelyLiving\Doorkeeper\Rules\RuleAbstract
     */
    private $prerequisite = null;

    /**
     * Random constructor.
     *
     * @param string $feature_id
     */
    public function __construct(string $feature_id)
    {
        $this->feature_id = $feature_id;
    }

    /**
     * @return string
     */
    final public function getFeatureId(): string
    {
        return $this->feature_id;
    }

    /**
     * @param \RemotelyLiving\Doorkeeper\Rules\RuleAbstract $rule
     *
     * @throws \DomainException
     */
    public function setPrerequisite(RuleAbstract $rule): void
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
