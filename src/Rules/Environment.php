<?php
namespace RemotelyLiving\Doorkeeper\Rules;

use RemotelyLiving\Doorkeeper\Identification;
use RemotelyLiving\Doorkeeper\Requestor;

class Environment extends RuleAbstract
{
    /**
     * @var \RemotelyLiving\Doorkeeper\Identification\Environment
     */
    private $environment;

    /**
     * @param string $environment
     */
    public function __construct(string $environment)
    {
        $this->environment = new Identification\Environment($environment);
    }

    /**
     * @inheritdoc
     */
    public function getValue()
    {
        return $this->environment->getIdentifier();
    }

    /**
     * @inheritdoc
     */
    protected function childCanBeSatisfied(Requestor $requestor = null): bool
    {
        return $this->requestorHasMatchingId($requestor, $this->environment);
    }
}
