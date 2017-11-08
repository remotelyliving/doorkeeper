<?php
namespace RemotelyLiving\Doorkeeper\Tests\Rules;

use PHPUnit\Framework\TestCase;
use RemotelyLiving\Doorkeeper\Rules\Random;
use RemotelyLiving\Doorkeeper\Rules\RuleAbstract;
use RemotelyLiving\Doorkeeper\Tests\Utility\RuleAbstractMock;

class AbstractTest extends TestCase
{
    /**
     * @var RuleAbstract
     */
    private $rule_abstract;

    protected function setUp()
    {
        $this->rule_abstract = new RuleAbstractMock();
    }

    /**
     * @test
     */
    public function jsonSerializes()
    {
        $this->rule_abstract->addPrerequisite(new Random());
        //@codingStandardsIgnoreStart
        $expected = '{"type":"RemotelyLiving\\\Doorkeeper\\\Tests\\\Utility\\\RuleAbstractMock","value":"mockValue","prerequisites":[{"type":"RemotelyLiving\\\Doorkeeper\\\Rules\\\Random","value":null,"prerequisites":[]}]}';
        //@codingStandardsIgnoreStop

        $this->assertEquals($expected, json_encode($this->rule_abstract));
    }
}
