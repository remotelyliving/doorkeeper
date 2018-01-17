<?php
namespace RemotelyLiving\Doorkeeper\Rules;

use RemotelyLiving\Doorkeeper\Requestor;

class RuntimeCallable extends RuleAbstract
{
    /**
     * @var callable
     */
    private $runtimeCallable;

    /**
     * @param callable $runtimeCallable
     */
    public function __construct(callable $runtimeCallable)
    {
        $this->runtimeCallable = $runtimeCallable;
    }

    /**
     * @inheritdoc
     */
    protected function childCanBeSatisfied(Requestor $requestor = null): bool
    {
        $localFn = $this->runtimeCallable;
        return $localFn($requestor);
    }
}
