<?php
namespace RemotelyLiving\Doorkeeper\Rules;

use RemotelyLiving\Doorkeeper\Identification;
use RemotelyLiving\Doorkeeper\Requestor;

class HttpHeader extends RuleAbstract
{
    const HEADER_KEY = 'doorkeeper';

    /**
     * @var \RemotelyLiving\Doorkeeper\Identification\HttpHeader
     */
    private $header;

    public function __construct(string $header_value)
    {
        $this->header = new Identification\HttpHeader($header_value);
    }

    public function getValue()
    {
        return $this->header->getIdentifier();
    }

    protected function childCanBeSatisfied(Requestor $requestor = null): bool
    {
        return $this->requestorHasMatchingId($requestor, $this->header);
    }
}
