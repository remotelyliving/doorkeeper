<?php
namespace RemotelyLiving\Doorkeeper\Rules;

use RemotelyLiving\Doorkeeper\Identification;
use RemotelyLiving\Doorkeeper\Requestor;

class IpAddress extends RuleAbstract
{
    /**
     * @var Identification\IpAddress
     */
    private $ip_address;

    public function __construct(string $ip_address)
    {
        $this->ip_address = new Identification\IpAddress($ip_address);
    }

    public function getValue()
    {
        return $this->ip_address->getIdentifier();
    }

    protected function childCanBeSatisfied(Requestor $requestor = null): bool
    {
        return $this->requestorHasMatchingId($requestor, $this->ip_address);
    }
}
