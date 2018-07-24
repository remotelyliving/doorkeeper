<?php
namespace RemotelyLiving\Doorkeeper\Rules;

use RemotelyLiving\Doorkeeper\Requestor;
use RemotelyLiving\Doorkeeper\Utilities;

class TimeBefore extends RuleAbstract
{
    /**
     * @var \DateTimeImmutable
     */
    private $time_before;

    /**
     * @var \RemotelyLiving\Doorkeeper\Utilities\Time
     */
    private $time_utility;

    public function __construct(string $time_before, Utilities\Time $time_utility = null)
    {
        $this->time_utility = $time_utility ?? new Utilities\Time();
        $this->time_before  = $this->time_utility->getImmutableDateTime($time_before);
    }

    public function getValue()
    {
        return $this->time_before->format('Y-m-d H:i:s');
    }

    protected function childCanBeSatisfied(Requestor $requestor = null): bool
    {
        return $this->time_before > $this->time_utility->getImmutableDateTime('now');
    }
}
