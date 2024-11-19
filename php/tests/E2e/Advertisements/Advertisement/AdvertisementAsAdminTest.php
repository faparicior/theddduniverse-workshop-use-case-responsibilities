<?php
declare(strict_types=1);

namespace E2e\Advertisements\Advertisement;

use Demo\App\Framework\Database\DatabaseConnection;
use Demo\App\Framework\DependencyInjectionResolver;
use Demo\App\Framework\FrameworkRequest;
use Demo\App\Framework\FrameworkResponse;
use Demo\App\Framework\Server;
use PHPUnit\Framework\TestCase;

final class AdvertisementAsAdminTest extends TestCase
{
    private const string ADVERTISEMENT_ID = '6fa00b21-2930-483e-b610-d6b0e5b19b29';
    private const string NON_EXISTENT_ADVERTISEMENT_ID = '99999999-2930-483e-b610-d6b0e5b19b29';
    private const string CIVIC_CENTER_ID = '0d5a994b-1603-4c87-accc-581a59e4457c';
    private const string MEMBER_ID = 'e95a8999-cb23-4fa2-9923-e3015ef30411';
    private const string ADVERTISEMENT_CREATION_DATE = '2024-02-03 13:30:23';
    private const string INVALID_EMAIL = 'emailtest.com';
    private const string ADMIN_ID = '91b5fa8c-6212-4c0f-862f-4dc1cb0472c4';

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

    public function testShouldDisableAnAdvertisementAsAdmin(): void
    {
        $this->withAdminUser();
        $this->withAnAdvertisementCreated();

        $request = new FrameworkRequest(
            FrameworkRequest::METHOD_PUT,
            'advertisements/' . self::ADVERTISEMENT_ID . '/disable',
            [
                'password' => 'myPassword',
            ],
            [
                'userSession' => self::ADMIN_ID,
            ]
        );
        $response = $this->server->route($request);

        self::assertEquals(FrameworkResponse::STATUS_OK, $response->statusCode());
        self::assertEquals(
            $this->successCommandResponse(),
            $response->data(),
        );

        $resultSet = $this->connection->query('select * from advertisements;');
        self::assertEquals('disabled', $resultSet[0]['status']);
    }

    public function testShouldEnableAnAdvertisementAsAdmin(): void
    {
        $this->withMemberUser('enabled');
        $this->withAdminUser();
        $this->withAnAdvertisementCreated('disabled');

        $request = new FrameworkRequest(
            FrameworkRequest::METHOD_PUT,
            'advertisements/' . self::ADVERTISEMENT_ID . '/enable',
            [
                'password' => 'myPassword',
            ],
            [
                'userSession' => self::ADMIN_ID,
            ]
        );
        $response = $this->server->route($request);

        self::assertEquals(FrameworkResponse::STATUS_OK, $response->statusCode());
        self::assertEquals(
            $this->successCommandResponse(),
            $response->data(),
        );

        $resultSet = $this->connection->query('select * from advertisements;');
        self::assertEquals('enabled', $resultSet[0]['status']);
    }

    public function testShouldApproveAnAdvertisementAsAdmin(): void
    {
        $this->withMemberUser('enabled');
        $this->withAdminUser();
        $this->withAnAdvertisementCreated('disabled', 'pending_for_approval');

        $request = new FrameworkRequest(
            FrameworkRequest::METHOD_PUT,
            'advertisements/' . self::ADVERTISEMENT_ID . '/approve',
            [
                'password' => 'myPassword',
            ],
            [
                'userSession' => self::ADMIN_ID,
            ]
        );
        $response = $this->server->route($request);

        self::assertEquals(FrameworkResponse::STATUS_OK, $response->statusCode());
        self::assertEquals(
            $this->successCommandResponse(),
            $response->data(),
        );

        $resultSet = $this->connection->query('select * from advertisements;');
        self::assertEquals('approved', $resultSet[0]['approval_status']);
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

    private function withAnAdvertisementCreated(string $status = 'enabled', string $approvalStatus = 'approved'): void
    {
        $this->connection->execute(sprintf("INSERT INTO advertisements (id, description, email, password, advertisement_date, status, approval_status, user_id, civic_center_id) VALUES ('%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s' )",
                self::ADVERTISEMENT_ID,
                'Dream advertisement ',
                'email@test.com',
                md5('myPassword'),
                self::ADVERTISEMENT_CREATION_DATE,
                $status,
                $approvalStatus,
                self::MEMBER_ID,
                self::CIVIC_CENTER_ID,
            )
        );
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

    private function invalidPasswordCommandResponse(): array
    {
        return [
            'errors' => 'Invalid password',
            'code' => 400,
            'message' => 'Invalid password',
        ];
    }

    private function notFoundCommandResponse(): array
    {
        return [
            'errors' => 'Advertisement not found with ID: 99999999-2930-483e-b610-d6b0e5b19b29',
            'code' => 404,
            'message' => 'Advertisement not found with ID: 99999999-2930-483e-b610-d6b0e5b19b29',
        ];
    }
}
