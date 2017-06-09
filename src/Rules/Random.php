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

    /**
     * @param \RemotelyLiving\Doorkeeper\Utilities\Randomizer|null $randomizer
     */
    public function __construct(Randomizer $randomizer = null)
    {
        $this->randomizer = $randomizer ?? new Randomizer();
    }

    /**
     * @inheritdoc
     */
    protected function childCanBeSatisfied(Requestor $requestor = null): bool
    {
        return ($this->randomizer->generateRangedRandomInt(1, 100)
            === $this->randomizer->generateRangedRandomInt(1, 100));
    }
}
