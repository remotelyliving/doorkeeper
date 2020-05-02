<?php

declare(strict_types=1);

namespace RemotelyLiving\Doorkeeper\Tests\Stubs;

use RemotelyLiving\Doorkeeper\Requestor;
use RemotelyLiving\Doorkeeper\Rules;

class AbstractRule extends Rules\AbstractRule
{
    /**
     * @inheritdoc
     */
    public function getValue()
    {
        return 'mockValue';
    }

    /**
     * @inheritdoc
     */
    public function childCanBeSatisfied(Requestor $requestor = null): bool
    {
        $requestor;
        return true;
    }
}
