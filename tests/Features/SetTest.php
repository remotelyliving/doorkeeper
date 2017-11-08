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
     */
    public function jsonSerializesFeatures()
    {
        $feature_1 = new Feature('feature1Id', false);
        $feature_2 = new Feature('feature2Id', true);

        $set = new Set([$feature_1, $feature_2]);
        //@codingStandardsIgnoreStart
        $expected_json = '{"features":{"feature1Id":{"name":"feature1Id","enabled":false,"rules":[]},"feature2Id":{"name":"feature2Id","enabled":true,"rules":[]}}}';
        //@codingStandardsIgnoreStop

        $this->assertEquals($expected_json, json_encode($set));
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
