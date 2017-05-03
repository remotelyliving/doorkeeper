<?php
namespace RemotelyLiving\Doorkeeper\Rules;

use RemotelyLiving\Doorkeeper\Identification;
use RemotelyLiving\Doorkeeper\Requestor;

class IpAddress extends RuleAbstract
{
    /**
     * @var \RemotelyLiving\Doorkeeper\Rules\IpAddress
     */
    private $ip_address;

    /**
     * IpAddress constructor.
     *
     * @param string $feature_id
     * @param string $ip_address
     */
    public function __construct(string $feature_id, string $ip_address)
    {
        parent::__construct($feature_id);

        $this->ip_address = new Identification\IpAddress($ip_address);
    }

    /**
     * @inheritdoc
     */
    protected function childCanBeSatisfied(Requestor $requestor = null): bool
    {
        if (!$this->requestorHasIdentity($requestor, Identification\IpAddress::class)) {
            return false;
        }

        return $requestor->getIdentifiationByClassName(Identification\IpAddress::class)
            ->equals($this->ip_address);
    }
}
