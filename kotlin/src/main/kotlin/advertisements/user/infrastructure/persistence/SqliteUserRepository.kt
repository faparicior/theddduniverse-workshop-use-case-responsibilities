package advertisements.user.infrastructure.persistence

import advertisements.user.domain.*
import advertisements.shared.value_object.*
import advertisements.user.domain.value_object.MemberNumber
import advertisements.user.domain.value_object.Role
import advertisements.user.domain.value_object.UserStatus
import framework.database.DatabaseConnection

class SqliteUserRepository(private val dbConnection: DatabaseConnection) : UserRepository {

    override fun findAdminById(id: UserId): AdminUser? {
        val result = dbConnection.query("SELECT * FROM users WHERE id = '${id.value()}'")
        if (!result.next()) {
            return null
        }

        return if (result.getString("role") == "admin") {
            AdminUser.fromDatabase(
                UserId(result.getString("id")),
                Email(result.getString("email")),
                Role.ADMIN,
                CivicCenterId.create(result.getString("civic_center_id")),
                UserStatus.fromString(result.getString("status"))
            )
        } else {
            null
        }
    }

    override fun findMemberById(id: UserId): MemberUser? {
        val result = dbConnection.query("SELECT * FROM users WHERE id = '${id.value()}'")
        if (!result.next()) {
            return null
        }

        return if (result.getString("role") == "member") {
            MemberUser.fromDatabase(
                UserId(result.getString("id")),
                Email(result.getString("email")),
                Role.MEMBER,
                MemberNumber(result.getString("member_number")),
                CivicCenterId.create(result.getString("civic_center_id")),
                UserStatus.fromString(result.getString("status"))
            )
        } else {
            null
        }
    }

    override fun findAdminOrMemberById(id: UserId): Any? {
        val result = dbConnection.query("SELECT * FROM users WHERE id = '${id.value()}'")
        if (!result.next()) {
            return null
        }

        return when (result.getString("role")) {
            "admin" -> AdminUser.fromDatabase(
                UserId(result.getString("id")),
                Email(result.getString("email")),
                Role.ADMIN,
                CivicCenterId.create(result.getString("civic_center_id")),
                UserStatus.fromString(result.getString("status"))
            )
            "member" -> MemberUser.fromDatabase(
                UserId(result.getString("id")),
                Email(result.getString("email")),
                Role.MEMBER,
                MemberNumber(result.getString("member_number")),
                CivicCenterId.create(result.getString("civic_center_id")),
                UserStatus.fromString(result.getString("status"))
            )
            else -> null
        }
    }

    override fun saveMember(member: MemberUser) {
        val query = if (isASignUp(member)) {
            """
            INSERT INTO users (id, email, password, role, member_number, civic_center_id, status) 
            VALUES ('${member.id().value()}', '${member.email().value()}', '${member.password()?.value()}', '${member.role().value()}', '${member.memberNumber().value()}', '${member.civicCenterId().value()}', '${member.status().value()}') 
            ON CONFLICT(id) DO UPDATE SET email = '${member.email().value()}', role = '${member.role().value()}', member_number = '${member.memberNumber().value()}', civic_center_id = '${member.civicCenterId().value()}', status = '${member.status().value()}';
            """
        } else {
            """
            INSERT INTO users (id, email, password, role, member_number, civic_center_id) 
            VALUES ('${member.id().value()}', '${member.email().value()}', '${member.password()?.value()}', '${member.role().value()}', '${member.memberNumber().value()}', '${member.civicCenterId().value()}') 
            ON CONFLICT(id) DO UPDATE SET email = '${member.email().value()}', password = '${member.password()?.value()}', role = '${member.role().value()}', member_number = '${member.memberNumber().value()}', civic_center_id = '${member.civicCenterId().value()}';
            """
        }
        dbConnection.execute(query)
    }

    private fun isASignUp(member: MemberUser): Boolean {
        return member.password() == null
    }
}
