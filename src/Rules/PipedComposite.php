<?php
namespace RemotelyLiving\Doorkeeper\Rules;

use RemotelyLiving\Doorkeeper\Identification;
use RemotelyLiving\Doorkeeper\Requestor;

class PipedComposite extends RuleAbstract
{
    /**
     * @var Identification\PipedComposite
     */
    private $piped_composite;

    /**
     * @param string $piped_composite
     */
    public function __construct(string $piped_composite)
    {
        $this->piped_composite = new Identification\PipedComposite($piped_composite);
    }

    /**
     * @inheritdoc
     */
    public function getValue()
    {
        return $this->piped_composite->getIdentifier();
    }

    /**
     * @inheritdoc
     */
    protected function childCanBeSatisfied(Requestor $requestor = null): bool
    {
        if (!$this->requestorHasIdentity($requestor, Identification\PipedComposite::class)) {
            return false;
        }

        return $requestor->getIdentifiationByClassName(Identification\PipedComposite::class)
            ->equals($this->piped_composite);
    }
}
