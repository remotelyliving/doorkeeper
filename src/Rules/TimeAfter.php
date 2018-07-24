<?php
namespace RemotelyLiving\Doorkeeper\Rules;

use RemotelyLiving\Doorkeeper\Requestor;
use RemotelyLiving\Doorkeeper\Utilities;

class TimeAfter extends RuleAbstract
{
    /**
     * @var \DateTimeImmutable
     */
    private $time_after;

    /**
     * @var \RemotelyLiving\Doorkeeper\Utilities\Time
     */
    private $time_utility;

    public function __construct(string $time_after, Utilities\Time $time_utility = null)
    {
        $this->time_utility = $time_utility ?? new Utilities\Time();
        $this->time_after   = $this->time_utility->getImmutableDateTime($time_after);
    }

    public function getValue()
    {
        return $this->time_after->format('Y-m-d H:i:s');
    }

    protected function childCanBeSatisfied(Requestor $requestor = null): bool
    {
        return $this->time_after < $this->time_utility->getImmutableDateTime('now');
    }
}
