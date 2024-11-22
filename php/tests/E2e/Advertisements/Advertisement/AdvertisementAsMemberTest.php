<?php
declare(strict_types=1);

namespace E2e\Advertisements\Advertisement;

use Demo\App\Framework\Database\DatabaseConnection;
use Demo\App\Framework\DependencyInjectionResolver;
use Demo\App\Framework\FrameworkRequest;
use Demo\App\Framework\FrameworkResponse;
use Demo\App\Framework\Server;
use PHPUnit\Framework\TestCase;

final class AdvertisementAsMemberTest extends TestCase
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

    protected function tearDown(): void
    {
        $this->connection->close();
    }

    public function testShouldPublishAnAdvertisementAsMember(): void
    {
        $this->withMemberUser('enabled');

        $request = new FrameworkRequest(
            FrameworkRequest::METHOD_POST,
            'advertisement',
            [
                'id' => self::ADVERTISEMENT_ID,
                'description' => 'Dream advertisement ',
                'password' => 'myPassword',
                'email' => 'email@test.com',
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
            $response->data(),
        );

        $resultSet = $this->connection->query('select * from advertisements;');
        self::assertEquals('Dream advertisement ', $resultSet[0][1]);
    }

    public function testShouldFailPublishingAnAdvertisementWithSameId(): void
    {
        $this->withMemberUser('enabled');
        $this->withAnAdvertisementCreated();

        $request = new FrameworkRequest(
            FrameworkRequest::METHOD_POST,
            'advertisement',
            [
                'id' => self::ADVERTISEMENT_ID,
                'description' => 'Dream advertisement ',
                'password' => 'myPassword',
                'email' => 'email@test.com',
                'memberId' => self::MEMBER_ID,
                'civicCenterId' => self::CIVIC_CENTER_ID,
            ],
            [
                'userSession' => self::MEMBER_ID,
            ]
        );

        $response = $this->server->route($request);
        self::assertEquals(FrameworkResponse::STATUS_BAD_REQUEST, $response->statusCode());
        self::assertEquals(
            $this->errorCommandResponse(
                FrameworkResponse::STATUS_BAD_REQUEST,
                sprintf('Advertisement with id %s already exists', self::ADVERTISEMENT_ID)
            ),
            $response->data(),
        );

        $resultSet = $this->connection->query('select * from advertisements;');
        self::assertEquals('Dream advertisement ', $resultSet[0][1]);
    }

    public function testShouldChangeAnAdvertisement(): void
    {
        $this->withMemberUser('enabled');
        $this->withAnAdvertisementCreated();

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

        self::assertEquals(FrameworkResponse::STATUS_OK, $response->statusCode());
        self::assertEquals(
            $this->successCommandResponse(),
            $response->data(),
        );

        $resultSet = $this->connection->query('select * from advertisements;');
        self::assertEquals('Dream advertisement changed ', $resultSet[0]['description']);
        $diff = date_diff(new \DateTime($resultSet[0]['advertisement_date']), new \DateTime(self::ADVERTISEMENT_CREATION_DATE));
        self::assertGreaterThan(0, $diff->days);
    }

    public function testShouldFailPublishingAnAdvertisementWithInvalidEmail(): void
    {
        $this->withMemberUser('enabled');

        $request = new FrameworkRequest(
            FrameworkRequest::METHOD_POST,
            'advertisement',
            [
                'id' => self::ADVERTISEMENT_ID,
                'description' => 'Dream advertisement ',
                'password' => 'myPassword',
                'email' => self::INVALID_EMAIL,
                'memberId' => self::MEMBER_ID,
                'civicCenterId' => self::CIVIC_CENTER_ID,
            ],
            [
                'userSession' => self::MEMBER_ID,
            ]
        );

        $response = $this->server->route($request);
        self::assertEquals(FrameworkResponse::STATUS_BAD_REQUEST, $response->statusCode());
        self::assertEquals(
            $this->errorCommandResponse(
                FrameworkResponse::STATUS_BAD_REQUEST,
                sprintf('Invalid email format %s', self::INVALID_EMAIL)
            ),
            $response->data(),
        );
    }

    public function testShouldRenewAdvertisement(): void
    {
        $this->withAnAdvertisementCreated();

        $request = new FrameworkRequest(
            FrameworkRequest::METHOD_PATCH,
            'advertisements/' . self::ADVERTISEMENT_ID,
            [
                'password' => 'myPassword',
            ]
        );
        $response = $this->server->route($request);

        self::assertEquals(FrameworkResponse::STATUS_OK, $response->statusCode());
        self::assertEquals(
            $this->successCommandResponse(),
            $response->data(),
        );

        $resultSet = $this->connection->query('select * from advertisements;');
        $diff = date_diff(new \DateTime($resultSet[0]['advertisement_date']), new \DateTime(self::ADVERTISEMENT_CREATION_DATE));
        self::assertGreaterThan(0, $diff->days);
    }

    public function testShouldNotChangeAnAdvertisementWithIncorrectPassword(): void
    {
        $this->withMemberUser('enabled');
        $this->withAnAdvertisementCreated();

        $request = new FrameworkRequest(
            FrameworkRequest::METHOD_PUT,
            'advertisements/' . self::ADVERTISEMENT_ID,
            [
                'id' => self::ADVERTISEMENT_ID,
                'description' => 'Dream advertisement changed ',
                'email' => 'email@test.com',
                'password' => 'myBadPassword',
                'memberId' => self::MEMBER_ID,
                'civicCenterId' => self::CIVIC_CENTER_ID,
            ],
            [
                'userSession' => self::MEMBER_ID,
            ]
        );

        $response = $this->server->route($request);

        self::assertEquals(FrameworkResponse::STATUS_BAD_REQUEST, $response->statusCode());
        self::assertEquals(
            $this->invalidPasswordCommandResponse(),
            $response->data(),
        );

        $resultSet = $this->connection->query('select * from advertisements;');
        self::assertEquals('Dream advertisement ', $resultSet[0]['description']);
        self::assertEquals(md5('myPassword'), $resultSet[0]['password']);
    }

    public function testShouldNotRenewAnAdvertisementWithIncorrectPassword(): void
    {
        $this->withAnAdvertisementCreated();

        $request = new FrameworkRequest(
            FrameworkRequest::METHOD_PATCH,
            'advertisements/' . self::ADVERTISEMENT_ID,
            [
                'password' => 'myBadPassword',
            ]
        );

        $response = $this->server->route($request);

        self::assertEquals(FrameworkResponse::STATUS_BAD_REQUEST, $response->statusCode());
        self::assertEquals(
            $this->invalidPasswordCommandResponse(),
            $response->data(),
        );

        $resultSet = $this->connection->query('select * from advertisements;');
        $diff = date_diff(new \DateTime($resultSet[0]['advertisement_date']), new \DateTime(self::ADVERTISEMENT_CREATION_DATE));
        self::equalTo($diff->days);
    }

    public function testShouldFailRenewingNonExistentAdvertisement(): void
    {
        $this->withAnAdvertisementCreated();

        $request = new FrameworkRequest(
            FrameworkRequest::METHOD_PATCH,
            'advertisements/' . self::NON_EXISTENT_ADVERTISEMENT_ID,
            [
                'password' => 'myPassword',
            ]
        );
        $response = $this->server->route($request);

        self::assertEquals(FrameworkResponse::STATUS_NOT_FOUND, $response->statusCode());
        self::assertEquals(
            $this->notFoundCommandResponse(),
            $response->data(),
        );
    }

    public function testShouldFailChangingNonExistentAdvertisement(): void
    {
        $this->withMemberUser('enabled');
        $this->withAnAdvertisementCreated();

        $request = new FrameworkRequest(
            FrameworkRequest::METHOD_PUT,
            'advertisements/' . self::NON_EXISTENT_ADVERTISEMENT_ID,
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

        self::assertEquals(FrameworkResponse::STATUS_NOT_FOUND, $response->statusCode());
        self::assertEquals(
            $this->notFoundCommandResponse(),
            $response->data(),
        );
    }

    public function testShouldDeleteAnAdvertisementAsMember(): void
    {
        $this->withMemberUser('enabled');
        $this->withAnAdvertisementCreated('disabled');

        $request = new FrameworkRequest(
            FrameworkRequest::METHOD_DELETE,
            'advertisements/' . self::ADVERTISEMENT_ID,
            [],
            [
                'userSession' => self::MEMBER_ID,
            ]
        );
        $response = $this->server->route($request);

        self::assertEquals(FrameworkResponse::STATUS_OK, $response->statusCode());
        self::assertEquals(
            $this->successCommandResponse(),
            $response->data(),
        );

        $resultSet = $this->connection->query('select * from advertisements;');
        self::assertCount(0, $resultSet);
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
