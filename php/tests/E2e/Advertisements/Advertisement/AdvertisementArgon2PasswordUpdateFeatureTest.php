<?php
declare(strict_types=1);

namespace E2e\Advertisements\Advertisement;

use Demo\App\Framework\Database\DatabaseConnection;
use Demo\App\Framework\DependencyInjectionResolver;
use Demo\App\Framework\FrameworkRequest;
use Demo\App\Framework\FrameworkResponse;
use Demo\App\Framework\Server;
use PHPUnit\Framework\TestCase;

final class AdvertisementArgon2PasswordUpdateFeatureTest extends TestCase
{
    private const string ADVERTISEMENT_ID = '6fa00b21-2930-483e-b610-d6b0e5b19b29';
    private const string ADVERTISEMENT_CREATION_DATE = '2024-02-03 13:30:23';
    private const string CIVIC_CENTER_ID = '0d5a994b-1603-4c87-accc-581a59e4457c';
    private const string MEMBER_ID = 'e95a8999-cb23-4fa2-9923-e3015ef30411';
    private DependencyInjectionResolver $resolver;
    private Server $server;
    private DatabaseConnection $connection;

    protected function setUp(): void
    {
        $this->resolver = new DependencyInjectionResolver();
        $this->connection = $this->resolver->connection();
        $this->emptyDatabase();
        $this->server = new Server($this->resolver);
        parent::setUp();
    }

    protected function tearDown(): void
    {
        $this->connection->close();
    }

    ////////////////////////////////////////////////////////////
    // Use this help
    // https://www.php.net/manual/es/function.password-hash.php

    public function testShouldPublishAnAdvertisementWithArgon2Password(): void
    {
        $this->withMemberUser('enabled');

        $request = new FrameworkRequest(
            FrameworkRequest::METHOD_POST,
            'advertisement',
            [
                'id' => self::ADVERTISEMENT_ID,
                'description' => 'Dream advertisement ',
                'email' => 'email@test.com',
                'password' => 'myPassword',
                'memberId' => self::MEMBER_ID,
                'civicCenterId' => self::CIVIC_CENTER_ID,
            ],
            [
                'userSession' => self::MEMBER_ID,
            ]
        );

        $response = $this->server->route($request);
        self::assertEquals(FrameworkResponse::STATUS_CREATED, $response->statusCode());
        self::assertEquals(
            $this->successCommandResponse(FrameworkResponse::STATUS_CREATED),
            $response->data()
        );

        $resultSet = $this->connection->query('select * from advertisements;');
        $this->expectHasAnArgon2Password($resultSet[0]['password']);
    }

    public function testShouldChangeToArgon2PasswordUpdatingAnAdvertisement(): void
    {
        $this->withMemberUser('enabled');
        $this->withAnAdvertisementWithAMd5PasswordCreated();

        $request = new FrameworkRequest(
            FrameworkRequest::METHOD_PUT,
            'advertisements/' . self::ADVERTISEMENT_ID,
            [
                'description' => 'Dream advertisement changed ',
                'email' => 'email@test.com',
                'password' => 'myPassword',
            ],
            [
                'userSession' => self::MEMBER_ID,
            ]
        );

        $response = $this->server->route($request);

        self::assertEquals(
            $this->successCommandResponse(),
            $response->data()
        );

        $resultSet = $this->connection->query('select * from advertisements;');
        $this->expectHasAnArgon2Password($resultSet[0]['password']);
    }

    public function testShouldChangeToArgon2PasswordRenewingAnAdvertisement(): void
    {
        $this->withAnAdvertisementWithAMd5PasswordCreated();

        $request = new FrameworkRequest(
            FrameworkRequest::METHOD_PATCH,
            'advertisements/' . self::ADVERTISEMENT_ID,
            [
                'password' => 'myPassword',
            ],
            [
                'userSession' => self::MEMBER_ID,
            ]
        );

        $response = $this->server->route($request);

        self::assertEquals(
            $this->successCommandResponse(),
            $response->data()
        );

        $resultSet = $this->connection->query('select * from advertisements;');
        $this->expectHasAnArgon2Password($resultSet[0]['password']);
    }


    private function emptyDatabase(): void
    {
        $this->connection->execute('delete from advertisements;');
        $this->connection->execute('delete from users;');
    }

    private function withMemberUser(string $status): void
    {
        $this->connection->execute(sprintf("INSERT INTO users (id, email, password, role, member_number, civic_center_id, status) VALUES ('%s', '%s', '%s', '%s', '%s', '%s', '%s')",
                self::MEMBER_ID,
                'member@test.com',
                md5('myPassword'),
                'member',
                '123456',
                self::CIVIC_CENTER_ID,
                $status,
            )
        );
    }
    private function withAnAdvertisementWithAMd5PasswordCreated(): void
    {
        $this->connection->execute(sprintf("INSERT INTO advertisements (id, description, email, password, advertisement_date, status, approval_status, user_id, civic_center_id) VALUES ('%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s' )",
                self::ADVERTISEMENT_ID,
                'Dream advertisement ',
                'email@test.com',
                md5('myPassword'),
                self::ADVERTISEMENT_CREATION_DATE,
                'active',
                'approved',
                self::MEMBER_ID,
                self::CIVIC_CENTER_ID,
            )
        );
    }

    private function expectHasAnArgon2Password($password): void
    {
        self::assertStringStartsWith('$argon2i$', $password);
    }

    private function successCommandResponse(int $statusCode = 200): array
    {
        return [
            'errors' => '',
            'code' => $statusCode,
            'message' => '',
        ];
    }
}
