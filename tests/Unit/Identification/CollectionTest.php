<?php

declare(strict_types=1);

namespace RemotelyLiving\Doorkeeper\Tests\Unit\Identification;

use PHPUnit\Framework\TestCase;
use RemotelyLiving\Doorkeeper\Identification;

class CollectionTest extends TestCase
{
    public function testInvalidClassThrowsException(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $collection = new Identification\Collection(
            Identification\Environment::class,
            [new Identification\Environment('dev')]
        )
        ;
        $collection->add(new Identification\HttpHeader('boop'));
    }

    public function testAddsAndGetsRemovesCountsAndHasAndIsIterableLol(): void
    {
        $prod = new Identification\Environment('prod');
        $dev = new Identification\Environment('dev');
        $collection = new Identification\Collection(Identification\Environment::class, [$dev]);
        $collection->add($prod);
        $collection->add($prod); // overwrites

        $this->assertEquals($dev, $collection->get('dev'));
        $this->assertEquals($prod, $collection->get('prod'));

        $this->assertTrue($collection->has($dev));
        $this->assertTrue($collection->has($prod));
        $this->assertFalse($collection->has(new Identification\Environment('derp')));

        $this->assertEquals(2, $collection->count());
        $collection->remove($dev);

        $this->assertFalse($collection->has($dev));
        $this->assertEquals(1, $collection->count());

        foreach ($collection as $id) {
            $this->assertEquals($prod, $id);
        }
    }
}
