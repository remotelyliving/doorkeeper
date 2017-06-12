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

    /**
     * @param string $header_value
     */
    public function __construct(string $header_value)
    {
        $this->header = new Identification\HttpHeader($header_value);
    }

    /**
     * @inheritdoc
     */
    public function getValue()
    {
        return $this->header->getIdentifier();
    }

    /**
     * @inheritdoc
     */
    protected function childCanBeSatisfied(Requestor $requestor = null): bool
    {
        if (!$this->requestorHasIdentity($requestor, Identification\HttpHeader::class)) {
            return false;
        }

        return $requestor->getIdentifiationByClassName(Identification\HttpHeader::class)
            ->equals($this->header);
    }
}
