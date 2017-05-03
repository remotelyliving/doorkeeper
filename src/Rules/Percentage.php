<?php
namespace RemotelyLiving\Doorkeeper\Rules;

use RemotelyLiving\Doorkeeper\Requestor;
use RemotelyLiving\Doorkeeper\Utilities\Randomizer;

class Percentage extends RuleAbstract
{
    /**
     * @var int
     */
    private $chances;

    /**
     * @var \RemotelyLiving\Doorkeeper\Utilities\Randomizer
     */
    private $randomizer;

    /**
     * Percentage constructor.
     *
     * @param string $feature_id
     * @param int    $percentage
     */
    public function __construct(string $feature_id, int $percentage, Randomizer $randomizer = null)
    {
        if ($percentage < 0 || $percentage > 100) {
            throw new \InvalidArgumentException("Percentage must be represented as a value from 1 to 100");
        }

        parent::__construct($feature_id);

        $this->chances = $percentage;
        $this->randomizer = $randomizer ?? new Randomizer();
    }

    /**
     * @inheritdoc
     */
    protected function childCanBeSatisfied(Requestor $requestor = null): bool
    {
        $lottery_number = $this->randomizer->generateRangedRandomInt(1, 100);

        if ($this->chances === 100) {
            return true;
        }

        for ($i = 0; $i < $this->chances; $i++) {
            if ($lottery_number === $this->randomizer->generateRangedRandomInt(1, $this->chances)) {
                return true;
            }
        }

        return false;
    }
}
