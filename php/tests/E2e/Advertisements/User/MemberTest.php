<?php

namespace E2e\Advertisements\User;

use Demo\App\Framework\Database\DatabaseConnection;
use Demo\App\Framework\DependencyInjectionResolver;
use Demo\App\Framework\FrameworkRequest;
use Demo\App\Framework\FrameworkResponse;
use Demo\App\Framework\Server;
use PHPUnit\Framework\TestCase;

final class MemberTest extends TestCase
{
    private const string MEMBER_ID = '6fa00b21-2930-483e-b610-d6b0e5b19b29';
    private const string ADMIN_ID = 'e95a8999-cb23-4fa2-9923-e3015ef30411';
    private const string CIVIC_CENTER_ID = '0d5a994b-1603-4c87-accc-581a59e4457c';
    private const string CIVIC_CENTER_2_ID = '5ddd994b-1603-4c87-accc-581a59e4457c';

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

    public function testShouldSignUpAMemberThroughAnAdmin(): void
    {
        $this->withAdminUser();

        $request = new FrameworkRequest(
            FrameworkRequest::METHOD_POST,
            'member/signup',
            [
                'id' => self::MEMBER_ID,
                'email' => 'member@test.com',
                'password' => 'password',
                'memberNumber' => '123456',
                'civicCenterId' => self::CIVIC_CENTER_ID,
            ],
            [
                'userSession' => self::ADMIN_ID,
            ]
        );

        $response = $this->server->route($request);
        self::assertEquals(FrameworkResponse::STATUS_CREATED, $response->statusCode());
        self::assertEquals(
            $this->successCommandResponse(FrameworkResponse::STATUS_CREATED),
            $response->data(),
        );

        $resultSet = $this->connection->query('select * from users where id = \'' . self::MEMBER_ID . '\';');
        self::assertCount(1, $resultSet);
    }

    public function testShouldDisableAMemberThroughAnAdmin(): void
    {
        $this->withAdminUser();
        $this->withMemberUser('enabled');

        $request = new FrameworkRequest(
            FrameworkRequest::METHOD_PUT,
            'member/' . self::MEMBER_ID . '/disable',
            [],
            [
                'userSession' => self::ADMIN_ID,
            ]
        );

        $response = $this->server->route($request);
        self::assertEquals(FrameworkResponse::STATUS_OK, $response->statusCode());
        self::assertEquals(
            $this->successCommandResponse(FrameworkResponse::STATUS_OK),
            $response->data(),
        );

        $resultSet = $this->connection->query('select * from users where id = \'' . self::MEMBER_ID . '\';');
        self::assertCount(1, $resultSet);
        self::assertEquals('disabled', $resultSet[0]['status']);
    }

    public function testShouldFailDisablingAMemberThroughAnAdminWithDifferentCivicCenter(): void
    {
        $this->withAdminUser();
        $this->withMemberUserFomDifferentCivicCenter();

        $request = new FrameworkRequest(
            FrameworkRequest::METHOD_PUT,
            'member/' . self::MEMBER_ID . '/disable',
            [],
            [
                'userSession' => self::ADMIN_ID,
            ]
        );

        $response = $this->server->route($request);
        self::assertEquals(FrameworkResponse::STATUS_BAD_REQUEST, $response->statusCode());
        self::assertEquals(
            $this->errorCommandResponse(
                FrameworkResponse::STATUS_BAD_REQUEST,
                sprintf('Admin does not belong to the same civic center')
            ),
            $response->data(),
        );
    }

    public function testShouldEnableADisabledAMemberThroughAnAdmin(): void
    {
        $this->withAdminUser();
        $this->withMemberUser('disabled');

        $request = new FrameworkRequest(
            FrameworkRequest::METHOD_PUT,
            'member/' . self::MEMBER_ID . '/enable',
            [],
            [
                'userSession' => self::ADMIN_ID,
            ]
        );

        $response = $this->server->route($request);
        self::assertEquals(FrameworkResponse::STATUS_OK, $response->statusCode());
        self::assertEquals(
            $this->successCommandResponse(FrameworkResponse::STATUS_OK),
            $response->data(),
        );

        $resultSet = $this->connection->query('select * from users where id = \'' . self::MEMBER_ID . '\';');
        self::assertCount(1, $resultSet);
        self::assertEquals('enabled', $resultSet[0]['status']);
    }

    public function testShouldFailSignUpAMemberThroughAnAdminWithDifferentCivicCenter(): void
    {
        $this->withAdminUser();

        $request = new FrameworkRequest(
            FrameworkRequest::METHOD_POST,
            'member/signup',
            [
                'id' => self::MEMBER_ID,
                'email' => 'member@test.com',
                'password' => 'password',
                'memberNumber' => '123456',
                'civicCenterId' => self::CIVIC_CENTER_2_ID,
            ],
            [
                'userSession' => self::ADMIN_ID,
            ]
        );

        $response = $this->server->route($request);

        self::assertEquals(FrameworkResponse::STATUS_BAD_REQUEST, $response->statusCode());
        self::assertEquals(
            $this->errorCommandResponse(
                FrameworkResponse::STATUS_BAD_REQUEST,
                sprintf('Admin does not belong to the same civic center')
            ),
            $response->data(),
        );
    }

    private function emptyDatabase(): void
    {
        $this->connection->execute('delete from users;');
    }

    private function successCommandResponse(int $code = 200): array
    {
        return [
            'errors' => '',
            'code' => $code,
            'message' => '',
        ];
    }

    private function errorCommandResponse(int $code = 400, string $message = ''): array
    {
        return [
            'errors' => $message,
            'code' => $code,
            'message' => $message,
        ];
    }

    private function withAdminUser(): void
    {
        $this->connection->execute(sprintf("INSERT INTO users (id, email, password, role, member_number, civic_center_id, status) VALUES ('%s', '%s', '%s', '%s', '%s', '%s', '%s')",
                self::ADMIN_ID,
                'admin@test.com',
                md5('myPassword'),
                'admin',
                '',
                self::CIVIC_CENTER_ID,
                'enabled',
            )
        );
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

    private function withMemberUserFomDifferentCivicCenter(): void
    {
        $this->connection->execute(sprintf("INSERT INTO users (id, email, password, role, member_number, civic_center_id, status) VALUES ('%s', '%s', '%s', '%s', '%s', '%s', '%s')",
                self::MEMBER_ID,
                'member@test.com',
                md5('myPassword'),
                'member',
                '123456',
                self::CIVIC_CENTER_2_ID,
                'enabled',
            )
        );
    }
}
