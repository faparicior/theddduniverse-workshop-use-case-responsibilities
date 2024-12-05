package advertisements.advertisement.infrastructure.persistence

import advertisements.advertisement.domain.Advertisement
import advertisements.advertisement.domain.value_object.ActiveAdvertisements
import advertisements.advertisement.domain.value_object.AdvertisementDate
import advertisements.advertisement.domain.value_object.AdvertisementId
import advertisements.advertisement.domain.value_object.Description
import advertisements.shared.value_object.CivicCenterId
import advertisements.shared.value_object.Email
import advertisements.shared.value_object.Password
import advertisements.shared.value_object.UserId
import advertisements.user.domain.MemberUser
import framework.database.DatabaseConnection
import java.time.LocalDateTime

class SqLiteAdvertisementRepository(private val connection: DatabaseConnection):
    advertisements.advertisement.domain.AdvertisementRepository {
    override fun save(advertisement: Advertisement) {
        val passwordHash = advertisement.password.value()
        connection.execute(
            "INSERT INTO advertisements (id, description, email, password, advertisement_date, civic_center_id, user_id, status, approval_status) VALUES ('" +
                    "${advertisement.id.value()}', '${advertisement.description.value()}', '${advertisement.email.value()}', '$passwordHash', '${advertisement.date.value()}', '${advertisement.civicCenterId.value()}', '${advertisement.memberId.value()}', '${advertisement.status.name}', '${advertisement.approvalStatus.name}') " +
                    "ON CONFLICT(id) DO UPDATE SET description = '${advertisement.description.value()}', email = '${advertisement.email.value()}', password = '${passwordHash}', advertisement_date = '${advertisement.date.value()}', civic_center_id = '${advertisement.civicCenterId.value()}', user_id = '${advertisement.memberId.value()}', status = '${advertisement.status.name}', approval_status = '${advertisement.approvalStatus.name}';"
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
            Email(result.getString("email")),
            Password.fromEncryptedPassword(result.getString("password")),
            AdvertisementDate(LocalDateTime.parse(result.getString("advertisement_date"))),
            CivicCenterId.create(result.getString("civic_center_id")),
            UserId(result.getString("user_id"))
        )
    }

    override fun activeAdvertisementsByMember(member: MemberUser): ActiveAdvertisements {
        val result = connection.query("SELECT COUNT(*) as active FROM advertisements WHERE user_id = '${member.id().value()}' AND status = 'active'")
        return ActiveAdvertisements.fromInt(result.getInt("active"))
    }

    override fun delete(advertisement: Advertisement) {
        connection.execute("DELETE FROM advertisements WHERE id = '${advertisement.id.value()}'")
    }
}
