<?php
namespace RemotelyLiving\Doorkeeper\Tests;

use PHPUnit\Framework\TestCase;
use Psr\Http\Message\RequestInterface;
use RemotelyLiving\Doorkeeper\Identification\Collection;
use RemotelyLiving\Doorkeeper\Identification\Environment;
use RemotelyLiving\Doorkeeper\Identification\HttpHeader;
use RemotelyLiving\Doorkeeper\Identification\IntegerId;
use RemotelyLiving\Doorkeeper\Identification\IpAddress;
use RemotelyLiving\Doorkeeper\Identification\PipedComposite;
use RemotelyLiving\Doorkeeper\Identification\StringHash;
use RemotelyLiving\Doorkeeper\Identification\UserId;
use RemotelyLiving\Doorkeeper\Requestor;

class RequestorTest extends TestCase
{

    /**
     * @test
     */
    public function getsAndSetIdentities()
    {
        $user_id         = 321;
        $int_id          = 123;
        $hash            = 'lkjelrkjelr';
        $env             = 'STAGE';
        $ip              = '192.168.1.1';
        $header          = 'someHeader';
        $piped_composite = 'cat|in|the|hat';

        $id_identification     = new UserId($user_id);
        $hash_identification   = new StringHash($hash);
        $env_identification    = new Environment($env);
        $ip_identification     = new IpAddress($ip);
        $header_identification = new HttpHeader($header);
        $pc_identification     = new PipedComposite($piped_composite);
        $int_identification    = new IntegerId($int_id);
        $request = $this->createMock(RequestInterface::class);
        $request->method('getHeaderLine')
            ->with(\RemotelyLiving\Doorkeeper\Rules\HttpHeader::HEADER_KEY)
            ->willReturn($header);

        $identifications_stored = [
            UserId::class => new Collection(UserId::class, [$id_identification]),
            StringHash::class => new Collection(StringHash::class, [$hash_identification]),
            Environment::class => new Collection(Environment::class, [$env_identification]),
            IpAddress::class => new Collection(IpAddress::class, [$ip_identification]),
            HttpHeader::class => new Collection(HttpHeader::class, [$header_identification]),
            PipedComposite::class => new Collection(PipedComposite::class, [$pc_identification]),
            IntegerId::class => new Collection(IntegerId::class, [$int_identification])
        ];

        $identifications_args = [
            $id_identification,
            $hash_identification,
            $env_identification,
            $ip_identification,
            $header_identification,
            $pc_identification,
            $int_identification
        ];

        $this->assertEquals(
            new Requestor($identifications_args),
            (new Requestor())
                ->withStringHash($hash)
                ->withUserId($user_id)
                ->withEnvironment($env)
                ->withIpAddress($ip)
                ->withRequest($request)
                ->withPipedComposite($piped_composite)
                ->withPipedComposite($piped_composite) // duplicate protection
                ->withIntegerId($int_id)

        );

        $this->assertEquals(
            (new Requestor($identifications_args))->getIdentificationCollections(),
            $identifications_stored
        );
    }

    /**
     * @test
     */
    public function getIdentityHash()
    {
        $id = new IntegerId(434);
        $expected_hash = md5(serialize([IntegerId::class => new Collection(IntegerId::class, [$id])]));
        $requestor = new Requestor([$id]);

        $this->assertEquals($expected_hash, $requestor->getIdentityHash());
    }

    /**
     * @test
     */
    public function hasIdentification()
    {
        $identification1 = new PipedComposite('Bliz|2');
        $identification2 = new PipedComposite('Blaz|1');
        $requestor       = new Requestor([$identification1, $identification2]);

        $this->assertTrue($requestor->hasIdentification($identification1));
        $this->assertTrue($requestor->hasIdentification($identification2));
        $this->assertFalse($requestor->hasIdentification(new HttpHeader('thing')));
    }
}
