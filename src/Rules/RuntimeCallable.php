<?php

declare(strict_types=1);

namespace RemotelyLiving\Doorkeeper\Rules;

use RemotelyLiving\Doorkeeper\RequestorInterface;

final class RuntimeCallable extends AbstractRule
{
    /**
     * @var callable
     */
    private $runtimeCallable;

    public function __construct(callable $runtimeCallable)
    {
        $this->runtimeCallable = $runtimeCallable;
    }

    protected function childCanBeSatisfied(RequestorInterface $requestor = null): bool
    {
        $localFn = $this->runtimeCallable;
        return (bool) $localFn($requestor);
    }
}
