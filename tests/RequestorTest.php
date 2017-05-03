<?php
namespace RemotelyLiving\Doorkeeper\Tests;

use PHPUnit\Framework\TestCase;
use Psr\Http\Message\RequestInterface;
use RemotelyLiving\Doorkeeper\Identification\Environment;
use RemotelyLiving\Doorkeeper\Identification\HttpHeader;
use RemotelyLiving\Doorkeeper\Identification\IntegerId;
use RemotelyLiving\Doorkeeper\Identification\IpAddress;
use RemotelyLiving\Doorkeeper\Identification\StringHash;
use RemotelyLiving\Doorkeeper\Requestor;

class RequestorTest extends TestCase
{

    /**
     * @test
     */
    public function getsAndSetIdentities()
    {
        $user_id = 321;
        $hash    = 'lkjelrkjelr';
        $env     = 'STAGE';
        $ip      = '192.168.1.1';
        $header  = 'someHeader';

        $id_identification     = new IntegerId($user_id);
        $hash_identification   = new StringHash($hash);
        $env_identification    = new Environment($env);
        $ip_identification     = new IpAddress($ip);
        $header_identification = new HttpHeader($header);

        $request = $this->createMock(RequestInterface::class);
        $request->method('getHeaderLine')
            ->with(\RemotelyLiving\Doorkeeper\Rules\HttpHeader::HEADER_KEY)
            ->willReturn($header);

        $identifications = [
            IntegerId::class   => $id_identification,
            StringHash::class  => $hash_identification,
            Environment::class => $env_identification,
            IpAddress::class   => $ip_identification,
            HttpHeader::class  => $header_identification,
        ];

        $this->assertEquals(
            new Requestor($identifications),
            (new Requestor())
                ->withStringHash($hash)
                ->withUserId($user_id)
                ->withEnvironment($env)
                ->withIpAddress($ip)
                ->withRequest($request)
        );

        $this->assertEquals((new Requestor($identifications))->getIdentifications(), $identifications);
    }

    /**
     * @test
     */
    public function getIdentityHash()
    {
        $identifications = [IntegerId::class => new IntegerId(434)];
        $requestor       = new Requestor($identifications);

        $this->assertEquals(md5(serialize($identifications)), $requestor->getIdentityHash());
    }

    /**
     * @test
     */
    public function getIdentificationByClassName()
    {
        $identification = new IntegerId(434);
        $requestor      = new Requestor([$identification]);

        $this->assertSame($identification, $requestor->getIdentifiationByClassName(IntegerId::class));
        $this->assertNull($requestor->getIdentifiationByClassName('boop'));
    }
}
