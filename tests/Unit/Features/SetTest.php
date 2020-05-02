<?php

declare(strict_types=1);

namespace RemotelyLiving\Doorkeeper\Tests\Unit\Features;

use PHPUnit\Framework\TestCase;
use RemotelyLiving\Doorkeeper\Features;

class SetTest extends TestCase
{
    public function testPushesQueriesAndGetsFeatures(): void
    {
        $feature1 = new Features\Feature('feature1Id', false);
        $feature2 = new Features\Feature('feature2Id', true);

        $set = new Features\Set([$feature1, $feature2]);

        $this->assertEquals(['feature1Id' => $feature1, 'feature2Id' => $feature2], $set->getFeatures());
        $this->assertEquals($feature1, $set->getFeatureByName($feature1->getName()));
        $this->assertFalse($set->offsetExists('boop'));
        $this->assertTrue($set->offsetExists($feature2->getName()));
    }

    public function testJsonSerializesFeatures(): void
    {
        $feature_1 = new Features\Feature('feature1Id', false);
        $feature_2 = new Features\Feature('feature2Id', true);

        $set = new Features\Set([$feature_1, $feature_2]);
        // phpcs:disable
        $expected_json = '{"features":{"feature1Id":{"name":"feature1Id","enabled":false,"rules":[]},"feature2Id":{"name":"feature2Id","enabled":true,"rules":[]}}}';
        // phpcs:enable

        $this->assertEquals($expected_json, json_encode($set));
    }

    public function testThrowsOutOfBoundsExceptionWhenFeatureNotFound(): void
    {
        $this->expectException(\OutOfBoundsException::class);
        (new Features\Set())->getFeatureByName('slkf');
    }
}
