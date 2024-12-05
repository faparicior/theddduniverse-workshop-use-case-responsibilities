package framework.securityuser

import framework.database.DatabaseConnection

class SqliteSecurityUserRepository(private val connection: DatabaseConnection) : SecurityUserRepository {

    override fun findUserById(id: String): SecurityUser? {
        val result = connection.query("SELECT * FROM users WHERE id = '$id'")
        if (!result.next()) {
            return null
        }

        return SecurityUser(
            result.getString("id"),
            result.getString("email"),
            result.getString("password"),
            result.getString("role"),
            result.getString("status"),
        )
    }
}
