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
     * @var null|\RemotelyLiving\Doorkeeper\Utilities\Time
     */
    private $time_utility;

    /**
     * @param string                                         $feature_id
     * @param string                                         $time_after
     * @param \RemotelyLiving\Doorkeeper\Utilities\Time|null $time_utility
     */
    public function __construct(string $feature_id, string $time_after, Utilities\Time $time_utility = null)
    {
        parent::__construct($feature_id);

        $this->time_utility = $time_utility ?? new Utilities\Time();
        $this->time_after   = $this->time_utility->getImmutableDateTime($time_after);
    }

    /**
     * @inheritdoc
     */
    protected function childCanBeSatisfied(Requestor $requestor = null): bool
    {
        $requestor;

        return $this->time_after < $this->time_utility->getImmutableDateTime('now');
    }
}
