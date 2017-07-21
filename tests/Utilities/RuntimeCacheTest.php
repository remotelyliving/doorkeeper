<?php
namespace RemotelyLiving\Doorkeeper\Tests\Utilities;

use PHPUnit\Framework\TestCase;
use RemotelyLiving\Doorkeeper\Utilities\RuntimeCache;
use RemotelyLiving\Doorkeeper\Utilities\Time;

class RuntimeCacheTest extends TestCase
{
    /**
     * @var \RemotelyLiving\Doorkeeper\Utilities\RuntimeCache
     */
    private $sut;

    protected function setUp()
    {
        parent::setUp();

        $this->sut = new RuntimeCache();
    }

    /**
     * @test
     */
    public function getNotCached()
    {
        $this->assertNull($this->sut->get('boop'));
    }

    /**
     * @test
     */
    public function getNotCachedUsesCallback()
    {
        $fallback = function () {
            return 'beep';
        };

        $this->assertEquals('beep', $this->sut->get('boop', $fallback));
    }

    /**
     * @test
     */
    public function setWorksAndGetIgnoresFallback()
    {
        $this->sut->set('herp', ['derp']);

        $this->assertEquals(['derp'], $this->sut->get(
            'herp',
            function () {
                return 'wrong';
            }
        ));
    }

    /**
     * @test
     */
    public function destroy()
    {

        $this->sut->set('boop', 'beep');
        $this->sut->set('herp', 'derp');
        $this->sut->destroy('boop');

        $this->assertEquals('derp', $this->sut->get('herp'));
        $this->assertNull($this->sut->get('boop'));
    }

    /**
     * @test
     */
    public function setsCacheLimit()
    {
        $cache = new RuntimeCache(2);

        $cache->set('first', 1);

        $this->assertEquals(1, $cache->get('first'));

        $cache->set('second', 2);
        $cache->set('third', 3);

        $this->assertNull($cache->get('first'));
        $this->assertEquals(3, $cache->get('third'));
        $this->assertEquals(2, $cache->get('second'));
    }
}
