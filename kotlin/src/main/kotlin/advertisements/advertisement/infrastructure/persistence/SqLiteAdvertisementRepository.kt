package advertisements.advertisement.infrastructure.persistence

import advertisements.advertisement.domain.Advertisement
import advertisements.advertisement.domain.value_object.AdvertisementDate
import advertisements.advertisement.domain.value_object.AdvertisementId
import advertisements.advertisement.domain.value_object.Description
import advertisements.shared.value_object.Password
import framework.database.DatabaseConnection
import java.time.LocalDateTime

class SqLiteAdvertisementRepository(private val connection: DatabaseConnection):
    advertisements.advertisement.domain.AdvertisementRepository {
    override fun save(advertisement: Advertisement) {
        val passwordHash = advertisement.password.value()
        connection.execute(
            "INSERT INTO advertisements (id, description, password, advertisement_date) VALUES ('" +
                    "${advertisement.id.value()}', '${advertisement.description.value()}', '$passwordHash', '${advertisement.date.value()}') " +
                    "ON CONFLICT(id) DO UPDATE SET description = '${advertisement.description.value()}', password = '${passwordHash}', advertisement_date = '${advertisement.date.value()}';"
        )
    }

    override fun findById(id: AdvertisementId): Advertisement? {
        val result = connection.query(
            "SELECT * FROM advertisements WHERE id = '${id.value()}'"
        )

        if (!result.next()) {
            return null
        }

        return Advertisement(
            AdvertisementId(result.getString("id")),
            Description(result.getString("description")),
            Password.fromEncryptedPassword(result.getString("password")),
            AdvertisementDate(LocalDateTime.parse(result.getString("advertisement_date"))),
        )
    }
}
