<?php
namespace RemotelyLiving\Doorkeeper\Tests;

use PHPUnit\Framework\TestCase;
use Psr\Log\LoggerInterface;
use RemotelyLiving\Doorkeeper\Doorkeeper;
use RemotelyLiving\Doorkeeper\Features\Feature;
use RemotelyLiving\Doorkeeper\Features\Set;
use RemotelyLiving\Doorkeeper\Requestor;
use RemotelyLiving\Doorkeeper\Rules;

class DoorKeeperTest extends TestCase
{
    private $feature_set;

    private $logger;

    /**
     * @var \RemotelyLiving\Doorkeeper\Doorkeeper
     */
    private $sut;

    protected function setUp()
    {
        parent::setUp();

        $feature_1 = new Feature('new shiny', true, [
            new Rules\HttpHeader('new shiny', 'header'),
            new Rules\UserId('new shiny', 321),
            new Rules\Environment('new shiny', 'DEV'),
        ]);

        $feature_2 = new Feature('killer app', true, [
            new Rules\Percentage('killer app', 100),
        ]);

        $feature_3 = new Feature('disabled app', false, [
            new Rules\Percentage('disabled app', 100),
        ]);

        $feature_4 = new Feature('no', true, [
            new Rules\StringHash('no', 'hash')
        ]);

        $feature_5         = new Feature('no rules', true);
        $this->feature_set = new Set([$feature_1, $feature_2, $feature_3, $feature_4, $feature_5]);
        $this->logger      = $this->createMock(LoggerInterface::class);
        $this->sut         = new Doorkeeper($this->feature_set, $this->logger);
    }

    /**
     * @test
     */
    public function getsAndSetsRequestor()
    {
        $this->assertNull($this->sut->getRequestor());

        $this->sut->setRequestor(new Requestor());

        $this->assertEquals(new Requestor(), $this->sut->getRequestor());
    }

    /**
     * @test
     *
     * @expectedException \DomainException
     */
    public function protectsAgainstResettingInstanceRequestor()
    {
        $this->sut->setRequestor(new Requestor());
        $this->sut->setRequestor(new Requestor());
    }

    /**
     * @test
     */
    public function grantsAccessToFeatureWithoutRules()
    {
        $this->assertTrue($this->sut->grantsAccessTo('no rules'));
        $this->assertFalse($this->sut->grantsAccessTo('no'));
    }

    /**
     * @test
     */
    public function requestor()
    {
        $requestor = (new Requestor())
            ->withEnvironment('DEV')
            ->withUserId(9);

        $this->sut->setRequestor($requestor);
        $this->assertTrue($this->sut->grantsAccessTo('new shiny'));
        $this->assertFalse($this->sut->grantsAccessTo('disabled app'));
        $this->assertTrue($this->sut->grantsAccessTo('killer app'));
        $this->assertFalse($this->sut->grantsAccessTo('no'));
        $this->assertFalse($this->sut->grantsAccessTo('does not exist'));
        $this->assertTrue($this->sut->grantsAccessTo('new shiny'));
    }

    /**
     * @test
     */
    public function flushInstanceCache()
    {
        $doorkeeper = new Doorkeeper($this->feature_set, $this->logger);

        $this->assertEquals($this->sut, $doorkeeper);

        $this->sut->grantsAccessTo('no');

        $this->assertNotEquals($this->sut, $doorkeeper);

        $this->sut->flushRuntimeCache();

        $this->assertEquals($this->sut, $doorkeeper);
    }

    /**
     * @test
     */
    public function operatesWithoutLogger()
    {
        $doorkeeper = new Doorkeeper($this->feature_set);
        $this->assertFalse($doorkeeper->grantsAccessTo('no'));
    }
}
