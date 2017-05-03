<?php
namespace RemotelyLiving\Doorkeeper\Rules;

use RemotelyLiving\Doorkeeper\Requestor;
use RemotelyLiving\Doorkeeper\Utilities\Randomizer;

class Random extends RuleAbstract
{
    /**
     * @var \RemotelyLiving\Doorkeeper\Utilities\Randomizer
     */
    private $randomizer;

    public function __construct(string $feature_id, Randomizer $randomizer = null)
    {
        parent::__construct($feature_id);

        $this->randomizer = $randomizer ?? new Randomizer();
    }

    /**
     * @inheritdoc
     */
    protected function childCanBeSatisfied(Requestor $requestor = null): bool
    {
        return ($this->randomizer->generateRangedRandomInt(1, 100000) % 2 === 0);
    }
}
