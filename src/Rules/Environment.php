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

    public function __construct(string $environment)
    {
        $this->environment = new Identification\Environment($environment);
    }

    public function getValue()
    {
        return $this->environment->getIdentifier();
    }

    protected function childCanBeSatisfied(Requestor $requestor = null): bool
    {
        return $this->requestorHasMatchingId($requestor, $this->environment);
    }
}
