<?php

declare(strict_types=1);

namespace RemotelyLiving\Doorkeeper\Tests;

use PHPUnit\Framework\TestCase;
use Psr\Http;
use RemotelyLiving\Doorkeeper\Identification;
use RemotelyLiving\Doorkeeper\Requestor;
use RemotelyLiving\Doorkeeper\Rules;

class RequestorTest extends TestCase
{
    public function testGetsAndSetIdentities(): void
    {
        $userId = 321;
        $intId = 123;
        $hash = 'lkjelrkjelr';
        $env = 'STAGE';
        $ip = '192.168.1.1';
        $header = 'someHeader';
        $pipedComposite = 'cat|in|the|hat';

        $idIdentification = new Identification\UserId($userId);
        $hashIdentification = new Identification\StringHash($hash);
        $envIdentification = new Identification\Environment($env);
        $ipIdentification = new Identification\IpAddress($ip);
        $headerIdentification = new Identification\HttpHeader($header);
        $pcIdentification = new Identification\PipedComposite($pipedComposite);
        $intIdentification = new Identification\IntegerId($intId);
        $request = $this->createMock(Http\Message\RequestInterface::class);
        $request->method('getHeaderLine')
            ->with(Rules\HttpHeader::HEADER_KEY)
            ->willReturn($header);

        // phpcs:disable
        $identifications_stored = [
            Identification\UserId::class => new Identification\Collection(Identification\UserId::class, [$idIdentification]),
            Identification\StringHash::class => new Identification\Collection(Identification\StringHash::class, [$hashIdentification]),
            Identification\Environment::class => new Identification\Collection(Identification\Environment::class, [$envIdentification]),
            Identification\IpAddress::class => new Identification\Collection(Identification\IpAddress::class, [$ipIdentification]),
            Identification\HttpHeader::class => new Identification\Collection(Identification\HttpHeader::class, [$headerIdentification]),
            Identification\PipedComposite::class => new Identification\Collection(Identification\PipedComposite::class, [$pcIdentification]),
            Identification\IntegerId::class => new Identification\Collection(Identification\IntegerId::class, [$intIdentification])
       ];
        // phpcs:enabled

        $identificationsArgs = [
            $idIdentification,
            $hashIdentification,
            $envIdentification,
            $ipIdentification,
            $headerIdentification,
            $pcIdentification,
            $intIdentification
        ];

        $this->assertEquals(
            new Requestor($identificationsArgs),
            (new Requestor())
                ->withStringHash($hash)
                ->withUserId($userId)
                ->withEnvironment($env)
                ->withIpAddress($ip)
                ->withRequest($request)
                ->withPipedComposite($pipedComposite)
                ->withPipedComposite($pipedComposite) // duplicate protection
                ->withIntegerId($intId)
        );

        $this->assertEquals(
            (new Requestor($identificationsArgs))->getIdentificationCollections(),
            $identifications_stored
        );
    }

    public function testGetIdentityHash(): void
    {
        $id = new Identification\IntegerId(434);
        $collection = new Identification\Collection(Identification\IntegerId::class, [$id]);
        $expected_hash = md5(serialize([Identification\IntegerId::class => $collection]));
        $requestor = new Requestor([$id]);

        $this->assertEquals($expected_hash, $requestor->getIdentityHash());
    }

    public function testHasIdentification(): void
    {
        $identification1 = new Identification\PipedComposite('Bliz|2');
        $identification2 = new Identification\PipedComposite('Blaz|1');
        $requestor = new Requestor([$identification1, $identification2]);

        $this->assertTrue($requestor->hasIdentification($identification1));
        $this->assertTrue($requestor->hasIdentification($identification2));
        $this->assertFalse($requestor->hasIdentification(new Identification\HttpHeader('thing')));
    }
}
