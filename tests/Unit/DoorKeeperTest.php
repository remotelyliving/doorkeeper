<?php

declare(strict_types=1);

namespace RemotelyLiving\Doorkeeper\Tests;

use PHPUnit\Framework\TestCase;
use Psr\Log;
use RemotelyLiving\Doorkeeper\Doorkeeper;
use RemotelyLiving\Doorkeeper\Features;
use RemotelyLiving\Doorkeeper\Requestor;
use RemotelyLiving\Doorkeeper\Rules;

class DoorKeeperTest extends TestCase
{
    private Features\Set $featureSet;

    private Log\LoggerInterface $logger;

    private Doorkeeper $doorkeeper;

    protected function setUp(): void
    {
        parent::setUp();

        $feature1 = new Features\Feature('new shiny', true, [
            new Rules\HttpHeader('header'),
            new Rules\UserId(321),
            new Rules\Environment('DEV'),
        ]);

        $feature2 = new Features\Feature('killer app', true, [
            new Rules\Percentage(100),
        ]);

        $feature3 = new Features\Feature('disabled app', false, [
            new Rules\Percentage(100),
        ]);

        $feature4 = new Features\Feature('no', true, [
            new Rules\StringHash('hash')
        ]);

        $feature5 = new Features\Feature('no rules', true);
        $this->featureSet = new Features\Set([$feature1, $feature2, $feature3, $feature4, $feature5]);
        $this->logger = new Log\Test\TestLogger();
        $this->doorkeeper = new Doorkeeper($this->featureSet, null, $this->logger);
    }

    public function testGetsAndSetsRequestor(): void
    {
        $this->assertNull($this->doorkeeper->getRequestor());

        $this->doorkeeper->setRequestor(new Requestor());

        $this->assertEquals(new Requestor(), $this->doorkeeper->getRequestor());
    }

    public function testProtectsAgainstResettingInstanceRequestor(): void
    {
        $this->doorkeeper->setRequestor(new Requestor());
        $this->expectException(\DomainException::class);
        $this->doorkeeper->setRequestor(new Requestor());
    }

    public function testGrantsAccessToFeatureWithoutRules(): void
    {
        $this->assertTrue($this->doorkeeper->grantsAccessTo('no rules'));
        $this->assertFalse($this->doorkeeper->grantsAccessTo('no'));
    }

    public function testRequestor()
    {
        $requestor = (new Requestor())
            ->withEnvironment('DEV')
            ->withUserId(9);

        $this->doorkeeper->setRequestor($requestor);
        $this->assertTrue($this->doorkeeper->grantsAccessTo('new shiny'));
        $this->assertFalse($this->doorkeeper->grantsAccessTo('disabled app'));
        $this->assertTrue($this->doorkeeper->grantsAccessTo('killer app'));
        $this->assertFalse($this->doorkeeper->grantsAccessTo('no'));
        $this->assertFalse($this->doorkeeper->grantsAccessTo('does not exist'));
        $this->assertTrue($this->doorkeeper->grantsAccessTo('new shiny'));
    }

    public function testFlushRuntimeCache(): void
    {
        $doorkeeper = new Doorkeeper($this->featureSet, null, $this->logger);

        $this->assertEquals($this->doorkeeper, $doorkeeper);

        $this->doorkeeper->grantsAccessTo('no');

        $this->assertNotEquals($this->doorkeeper, $doorkeeper);

        $this->doorkeeper->flushRuntimeCache();

        $this->assertEquals($this->doorkeeper, $doorkeeper);
    }

    public function testOperatesWithoutLogger(): void
    {
        $doorkeeper = new Doorkeeper($this->featureSet);
        $this->assertFalse($doorkeeper->grantsAccessTo('no'));
    }
}
