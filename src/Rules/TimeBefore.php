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
     * @var null|\RemotelyLiving\Doorkeeper\Utilities\Time
     */
    private $time_utility;

    /**
     * @param string                                         $time_before
     * @param \RemotelyLiving\Doorkeeper\Utilities\Time|null $time_utility
     */
    public function __construct(string $time_before, Utilities\Time $time_utility = null)
    {
        $this->time_utility = $time_utility ?? new Utilities\Time();
        $this->time_before  = $this->time_utility->getImmutableDateTime($time_before);
    }

    /**
     * @inheritdoc
     */
    public function getValue()
    {
        return $this->time_before->format('Y-m-d H:i:s');
    }

    /**
     * @inheritdoc
     */
    protected function childCanBeSatisfied(Requestor $requestor = null): bool
    {
        $requestor;

        return $this->time_before > $this->time_utility->getImmutableDateTime('now');
    }
}
