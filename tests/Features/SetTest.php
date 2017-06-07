<?php
namespace RemotelyLiving\Tests\Doorkeeper\Features;

use PHPUnit\Framework\TestCase;
use RemotelyLiving\Doorkeeper\Features\Feature;
use RemotelyLiving\Doorkeeper\Features\Set;

class SetTest extends TestCase
{
    /**
     * @test
     */
    public function pushesQueriesAndGetsFeatures()
    {
        $feature_1 = new Feature('feature1Id', false);
        $feature_2 = new Feature('feature2Id', true);

        $set = new Set([$feature_1, $feature_2]);

        $this->assertEquals(['feature1Id' => $feature_1, 'feature2Id' => $feature_2], $set->getFeatures());
        $this->assertEquals($feature_1, $set->getFeatureByName($feature_1->getName()));
        $this->assertFalse($set->offsetExists('boop'));
        $this->assertTrue($set->offsetExists($feature_2->getName()));
    }

    /**
     * @test
     * @expectedException \OutOfBoundsException
     */
    public function throwsOutOfBoundsExceptionWhenFeatureNotFound()
    {
        (new Set())->getFeatureByName('slkf');
    }
}
