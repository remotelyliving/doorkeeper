<?php
namespace RemotelyLiving\Doorkeeper\Tests\Identification;

use PHPUnit\Framework\TestCase;
use RemotelyLiving\Doorkeeper\Identification\Collection;
use RemotelyLiving\Doorkeeper\Identification\Environment;
use RemotelyLiving\Doorkeeper\Identification\HttpHeader;

class CollectionTest extends TestCase
{
    /**
     * @test
     * @expectedException \InvalidArgumentException
     */
    public function invalidClassThrowsException()
    {
        $collection = new Collection(Environment::class, [new Environment('dev')]);
        $collection->add(new HttpHeader('boop'));
    }

    /**
     * @test
     */
    public function addsAndGetsRemovesCountsAndHasAndIsIterableLol()
    {
        $prod = new Environment('prod');
        $dev = new Environment('dev');
        $collection = new Collection(Environment::class, [$dev]);
        $collection->add($prod);
        $collection->add($prod); // overwrites

        $this->assertEquals($dev, $collection->get('dev'));
        $this->assertEquals($prod, $collection->get('prod'));

        $this->assertTrue($collection->has($dev));
        $this->assertTrue($collection->has($prod));
        $this->assertFalse($collection->has(new Environment('derp')));

        $this->assertEquals(2, $collection->count());
        $collection->remove($dev);

        $this->assertFalse($collection->has($dev));
        $this->assertEquals(1, $collection->count());

        foreach ($collection as $id) {
            $this->assertEquals($prod, $id);
        }
    }
}
