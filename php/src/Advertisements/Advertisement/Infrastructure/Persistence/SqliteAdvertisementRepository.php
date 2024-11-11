<?php
declare(strict_types=1);

namespace Demo\App\Advertisements\Advertisement\Infrastructure\Persistence;

use Demo\App\Advertisements\Advertisement\Domain\Advertisement;
use Demo\App\Advertisements\Advertisement\Domain\AdvertisementRepository;
use Demo\App\Advertisements\Advertisement\Domain\Exceptions\InvalidEmailException;
use Demo\App\Advertisements\Advertisement\Domain\ValueObjects\AdvertisementDate;
use Demo\App\Advertisements\Advertisement\Domain\ValueObjects\AdvertisementId;
use Demo\App\Advertisements\Advertisement\Domain\ValueObjects\Description;
use Demo\App\Advertisements\Shared\Exceptions\InvalidUniqueIdentifierException;
use Demo\App\Advertisements\Shared\ValueObjects\Email;
use Demo\App\Advertisements\Shared\ValueObjects\Password;
use Demo\App\Framework\Database\DatabaseConnection;
use Demo\App\Framework\database\SqliteConnection;

class SqliteAdvertisementRepository implements AdvertisementRepository
{
    private DatabaseConnection $dbConnection;
    public function __construct(SqliteConnection $connection)
    {
        $this->dbConnection = $connection;
    }

    public function save(Advertisement $advertisement): void
    {
        $this->dbConnection->execute(sprintf('
            INSERT INTO advertisements (id, description, email, password, advertisement_date) VALUES (\'%1$s\', \'%2$s\', \'%3$s\', \'%4$s\', \'%5$s\') 
            ON CONFLICT(id) DO UPDATE SET description = \'%2$s\', email = \'%3$s\', password = \'%4$s\', advertisement_date = \'%5$s\';',
                $advertisement->id()->value(),
                $advertisement->description()->value(),
                $advertisement->email()->value(),
                $advertisement->password()->value(),
                $advertisement->date()->value()->format('Y-m-d H:i:s')
            )
        );
    }

    /**
     * @throws InvalidEmailException
     * @throws InvalidUniqueIdentifierException
     */
    public function findById(AdvertisementId $id): ?Advertisement
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
            new AdvertisementDate(new \DateTime($row['advertisement_date']))
        );
    }
}