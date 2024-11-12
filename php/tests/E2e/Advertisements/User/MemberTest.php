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

    public function testShouldSignUpAMember(): void
    {
//        $request = new FrameworkRequest(
//            FrameworkRequest::METHOD_POST,
//            'advertisement',
//            [
//                'id' => self::ADVERTISEMENT_ID,
//                'description' => 'Dream advertisement ',
//                'password' => 'myPassword',
//                'email' => 'email@test.com',
//            ]
//        );
//
//        $response = $this->server->route($request);
//        self::assertEquals(FrameworkResponse::STATUS_CREATED, $response->statusCode());
//        self::assertEquals(
//            $this->successCommandResponse(FrameworkResponse::STATUS_CREATED),
//            $response->data(),
//        );
//
//        $resultSet = $this->connection->query('select * from advertisements;');
//        self::assertEquals('Dream advertisement ', $resultSet[0][1]);
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
}
