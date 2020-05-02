<?php

declare(strict_types=1);

namespace RemotelyLiving\Doorkeeper\Tests\Unit\Logger;

use RemotelyLiving\Doorkeeper\Identification;
use RemotelyLiving\Doorkeeper\Logger;
use PHPUnit\Framework\TestCase;
use RemotelyLiving\Doorkeeper\Requestor;

class ProcessorTest extends TestCase
{
    public function testInvokeNoRequestor(): void
    {
        $processor = new Logger\Processor();
        $record1 = [
          'context' => [Logger\Processor::FEATURE_ID => 'oye', Logger\Processor::CONTEXT_KEY_REQUESTOR => null]
        ];

        $record2 = ['context' => [Logger\Processor::FEATURE_ID => 'oye']];

        $this->assertSame($record1, $processor($record1));
        $this->assertSame($record2, $processor($record2));
    }

    public function testInvokeWithRequestor(): void
    {
        $requestor = (new Requestor())->withIpAddress('127.0.0.1')
          ->withUserId(123)
          ->withStringHash('hashymcgee')
          ->withStringHash('otherhashymgee');

        $processor = new Logger\Processor();
        $record = [
          'context' => [Logger\Processor::FEATURE_ID => 'oye', Logger\Processor::CONTEXT_KEY_REQUESTOR => $requestor]
        ];

        $expected_ids = '{"IpAddress":["127.0.0.1"],"UserId":[123],"StringHash":["hashymcgee","otherhashymgee"]}';
        $expectedContext = [
            'context' => [
              Logger\Processor::FEATURE_ID => 'oye',
              Logger\Processor::CONTEXT_KEY_REQUESTOR => [
                  'identifiers' => $expected_ids,
              ],
            ]
        ];

        $this->assertEquals($expectedContext, $processor($record));
    }


    public function testInvokeWithNoIdCollection(): void
    {
        $requestor = new Requestor();
        $processor = new Logger\Processor();
        $record = [
          'context' => [Logger\Processor::FEATURE_ID => 'oye', Logger\Processor::CONTEXT_KEY_REQUESTOR => $requestor]
        ];

        $expectedContext = [
          'context' => [
            Logger\Processor::FEATURE_ID => 'oye',
            Logger\Processor::CONTEXT_KEY_REQUESTOR => [],
          ]
        ];

        $this->assertEquals($expectedContext, $processor($record));
    }

    public function testInvokeWithRequestorAndFilteredFields(): void
    {
        $requestor = (new Requestor())->withIpAddress('127.0.0.1')
            ->withUserId(123)
            ->withStringHash('hashymcgee');

        $processor = new Logger\Processor();
        $processor->setFilteredIdentityTypes(
            [Identification\IpAddress::class, 'arbitrary', Identification\UserId::class]
        );

        $record = ['context' =>
          [Logger\Processor::FEATURE_ID => 'oye', Logger\Processor::CONTEXT_KEY_REQUESTOR => $requestor]
        ];

        $expectedContext = [
            'context' => [
                Logger\Processor::FEATURE_ID => 'oye',
                Logger\Processor::CONTEXT_KEY_REQUESTOR => [
                    'identifiers' => '{"StringHash":["hashymcgee"]}',
                ],
            ]
        ];

        $this->assertEquals($expectedContext, $processor($record));
    }
}
