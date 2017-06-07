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
     * @param string $feature_name
     * @param string $header_value
     */
    public function __construct(string $feature_name, string $header_value)
    {
        parent::__construct($feature_name);

        $this->header = new Identification\HttpHeader($header_value);
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
