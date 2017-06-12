<?php
namespace RemotelyLiving\Doorkeeper\Tests\Logger;

use RemotelyLiving\Doorkeeper\Logger\Processor;
use PHPUnit\Framework\TestCase;
use RemotelyLiving\Doorkeeper\Requestor;

class ProcessorTest extends TestCase
{
    /**
     * @test
     */
    public function invokeNoRequestor()
    {
        $processor = new Processor();
        $record_1  = [ 'context' => [ Processor::FEATURE_ID => 'oye', Processor::CONTEXT_KEY_REQUESTOR => null ] ];
        $record_2  = [ 'context' => [ Processor::FEATURE_ID => 'oye' ] ];

        $this->assertSame($record_1, $processor($record_1));
        $this->assertSame($record_2, $processor($record_2));
    }

    /**
     * @test
     */
    public function invokeWithRequestor()
    {
        $requestor = (new Requestor())->withIpAddress('127.0.0.1')
          ->withUserId(123)
          ->withStringHash('hashymcgee');

        $processor = new Processor();
        $record = [ 'context' => [ Processor::FEATURE_ID => 'oye', Processor::CONTEXT_KEY_REQUESTOR => $requestor ] ];

        $expected_context = [
            'context' => [
              Processor::FEATURE_ID => 'oye',
              Processor::CONTEXT_KEY_REQUESTOR => [
                'IpAddress' => '127.0.0.1',
                'UserId' => 123,
                'StringHash' => 'hashymcgee',
              ],
            ]
        ];

        $this->assertEquals($expected_context, $processor($record));
    }
}
