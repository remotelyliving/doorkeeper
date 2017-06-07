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
     * @param string                                               $feature_name
     * @param \RemotelyLiving\Doorkeeper\Utilities\Randomizer|null $randomizer
     */
    public function __construct(string $feature_name, Randomizer $randomizer = null)
    {
        parent::__construct($feature_name);

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
