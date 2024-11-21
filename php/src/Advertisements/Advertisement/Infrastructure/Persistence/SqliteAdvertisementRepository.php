<?php
declare(strict_types=1);

namespace Demo\App\Advertisements\Advertisement\Infrastructure\Persistence;

use Demo\App\Advertisements\Advertisement\Domain\Advertisement;
use Demo\App\Advertisements\Advertisement\Domain\AdvertisementRepository;
use Demo\App\Advertisements\Advertisement\Domain\Exceptions\AdvertisementNotFoundException;
use Demo\App\Advertisements\Advertisement\Domain\Exceptions\InvalidEmailException;
use Demo\App\Advertisements\Advertisement\Domain\ValueObjects\ActiveAdvertisements;
use Demo\App\Advertisements\Advertisement\Domain\ValueObjects\AdvertisementDate;
use Demo\App\Advertisements\Advertisement\Domain\ValueObjects\AdvertisementId;
use Demo\App\Advertisements\Advertisement\Domain\ValueObjects\Description;
use Demo\App\Advertisements\Shared\Exceptions\InvalidUniqueIdentifierException;
use Demo\App\Advertisements\Shared\ValueObjects\CivicCenterId;
use Demo\App\Advertisements\Shared\ValueObjects\Email;
use Demo\App\Advertisements\Shared\ValueObjects\Password;
use Demo\App\Advertisements\Shared\ValueObjects\UserId;
use Demo\App\Advertisements\User\Domain\MemberUser;
use Demo\App\Framework\Database\DatabaseConnection;
use Demo\App\Framework\database\SqliteConnection;

class SqliteAdvertisementRepository implements AdvertisementRepository
{
    private DatabaseConnection $dbConnection;
    public function __construct(SqliteConnection $connection)
    {
        $this->dbConnection = $connection;
    }

    public function delete(Advertisement $advertisement): void
    {
        $this->dbConnection->execute(sprintf('DELETE FROM advertisements WHERE id = \'%s\'', $advertisement->id()->value()));
    }

    public function save(Advertisement $advertisement): void
    {
        $this->dbConnection->execute(sprintf('
            INSERT INTO advertisements (id, description, email, password, advertisement_date, civic_center_id, user_id, status, approval_status) VALUES (\'%1$s\', \'%2$s\', \'%3$s\', \'%4$s\', \'%5$s\', \'%6$s\', \'%7$s\', \'%8$s\', \'%9$s\') 
            ON CONFLICT(id) DO UPDATE SET description = \'%2$s\', email = \'%3$s\', password = \'%4$s\', advertisement_date = \'%5$s\', civic_center_id = \'%6$s\', user_id = \'%7$s\', status = \'%8$s\', approval_status = \'%9$s\';',
                $advertisement->id()->value(),
                $advertisement->description()->value(),
                $advertisement->email()->value(),
                $advertisement->password()->value(),
                $advertisement->date()->value()->format('Y-m-d H:i:s'),
                $advertisement->civicCenterId()->value(),
                $advertisement->memberId()->value(),
                $advertisement->status()->value(),
                $advertisement->approvalStatus()->value(),
            )
        );
    }

    /**
     * @throws InvalidEmailException
     * @throws InvalidUniqueIdentifierException
     * @throws AdvertisementNotFoundException
     */
    public function findByIdOrFail(AdvertisementId $id): Advertisement
    {
        $advertisement = $this->findByIdOrNull($id);
        if (!$advertisement) {
            throw AdvertisementNotFoundException::withId($id->value());
        }

        return $advertisement;
    }

    public function findByIdOrNull(AdvertisementId $id): ?Advertisement
    {
        $result = $this->dbConnection->query(sprintf('SELECT * FROM advertisements WHERE id = \'%s\'', $id->value()));
        if(!$result) {
            return null;
        }

        $row = $result[0];
        return new Advertisement(
            new AdvertisementId($row['id']),
            new Description($row['description']),
            new Email($row['email']),
            Password::fromEncryptedPassword($row['password']),
            new AdvertisementDate(new \DateTime($row['advertisement_date'])),
            new CivicCenterId($row['civic_center_id']),
            new UserId($row['user_id']),
        );
    }

    public function activeAdvertisementsByMemberId(UserId $member): ActiveAdvertisements
    {
        $result = $this->dbConnection->query(sprintf('SELECT COUNT(*) as active FROM advertisements WHERE user_id = \'%s\' AND status = \'active\'', $member->value()));

        return ActiveAdvertisements::fromInt((int) $result[0]['active']);
    }
}
