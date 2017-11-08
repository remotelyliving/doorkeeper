<?php
namespace RemotelyLiving\Doorkeeper\Tests\Utility;

use RemotelyLiving\Doorkeeper\Requestor;
use RemotelyLiving\Doorkeeper\Rules\RuleAbstract;

class RuleAbstractMock extends RuleAbstract
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
