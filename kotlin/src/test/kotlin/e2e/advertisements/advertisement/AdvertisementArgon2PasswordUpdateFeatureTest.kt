package e2e.advertisements.advertisement

import framework.DependencyInjectionResolver
import framework.FrameworkRequest
import framework.FrameworkResponse
import framework.Server
import framework.database.DatabaseConnection
import org.junit.jupiter.api.Assertions
import org.junit.jupiter.api.BeforeEach
import org.junit.jupiter.api.Test
import java.security.MessageDigest
import java.time.LocalDateTime


class AdvertisementArgon2PasswordUpdateFeatureTest {
    companion object {
        private const val ADVERTISEMENT_CREATION_DATE = "2024-03-04T13:23:15"
        private const val DESCRIPTION = "Dream advertisement"
        private const val NEW_DESCRIPTION = "Dream advertisement changed"
        private const val ID = "6fa00b21-2930-483e-b610-d6b0e5b19b29"
        private const val PASSWORD = "myPassword"
        private const val MEMBER_ID = "e95a8999-cb23-4fa2-9923-e3015ef30411"

        private const val CIVIC_CENTER_ID = "0d5a994b-1603-4c87-accc-581a59e4457c"
    }

    private lateinit var connection: DatabaseConnection

    @BeforeEach
    fun init() {
        this.connection = DependencyInjectionResolver().connection()
        this.connection.execute("DELETE FROM advertisements")
        this.connection.execute("DELETE FROM users")
    }

    ////////////////////////////////////////////////////////////
    // Use this help
    // https://github.com/phxql/argon2-jvm

    @Test
    fun `should publish an advertisement with Argon2 password`() {
        withMemberUser("enabled") {

            val server = Server(DependencyInjectionResolver())

            val result = server.route(
                FrameworkRequest(
                    FrameworkRequest.METHOD_POST,
                    "advertisements",
                    mapOf(
                        "id" to ID,
                        "description" to DESCRIPTION,
                        "password" to PASSWORD,
                        "email" to "email@test.com",
                        "memberId" to MEMBER_ID,
                        "civicCenterId" to CIVIC_CENTER_ID
                    ),
                    mapOf(
                        "userSession" to MEMBER_ID
                    )
                )
            )

            Assertions.assertEquals(FrameworkResponse.STATUS_CREATED, result.statusCode)

            val resultSet = this.connection.query("SELECT * from advertisements;")
            var password = ""

            if (resultSet.next()) {
                password = resultSet.getString("password")
            }

            Assertions.assertTrue(password.startsWith("\$argon2i\$"))
        }
    }

    @Test
    fun `should change to Argon2 password updating an advertisement`() {
        withMemberUser("enabled") {
            withAnAdvertisementWithMd5Password {
                val server = Server(DependencyInjectionResolver())

                val result = server.route(FrameworkRequest(
                        FrameworkRequest.METHOD_PUT,
                        "advertisements/$ID",
                        mapOf(
                            "id" to ID,
                            "description" to NEW_DESCRIPTION,
                            "password" to PASSWORD,
                            "email" to "email@test.com",
                            "memberId" to MEMBER_ID,
                            "civicCenterId" to CIVIC_CENTER_ID
                        ),
                        mapOf(
                            "userSession" to MEMBER_ID
                        )
                    )
                )

                Assertions.assertEquals(FrameworkResponse.STATUS_OK, result.statusCode)

                val resultSet = this.connection.query("SELECT * from advertisements;")
                var password = ""

                if (resultSet.next()) {
                    password = resultSet.getString("password")
                }

                Assertions.assertTrue(password.startsWith("\$argon2i\$"))
            }
        }
    }

    @Test
    fun `should change to Argon2 password renewing an advertisement`() {
        withMemberUser("enabled") {
            withAnAdvertisementWithMd5Password {
                val server = Server(DependencyInjectionResolver())

                val result = server.route(
                    FrameworkRequest(
                        FrameworkRequest.METHOD_PATCH,
                        "advertisements/$ID",
                        mapOf(
                            "password" to PASSWORD,
                        ),
                        mapOf(
                            "userSession" to MEMBER_ID
                        )
                    )
                )

                Assertions.assertEquals(FrameworkResponse.STATUS_OK, result.statusCode)

                val resultSet = this.connection.query("SELECT * from advertisements;")
                var password = ""

                if (resultSet.next()) {
                    password = resultSet.getString("password")
                }

                Assertions.assertTrue(password.startsWith("\$argon2i\$"))
            }
        }
    }

    private fun withAnAdvertisementWithMd5Password(block: () -> Unit) {
        val password = PASSWORD.md5()
        val creationDate = LocalDateTime.parse(ADVERTISEMENT_CREATION_DATE).toString()
        val status = "active"
        val approvalStatus = "approved"
        this.connection.execute(
            """
            INSERT INTO advertisements (
                id, description, email, password, advertisement_date, status, approval_status, user_id, civic_center_id
            ) VALUES (
                '$ID', '$DESCRIPTION', 'email@test.com', '$password', '$creationDate', '$status', '$approvalStatus', '$MEMBER_ID', '$CIVIC_CENTER_ID'
            )
            """
        )
        block()
    }

    private fun String.md5(): String {
        val md = MessageDigest.getInstance("MD5")
        val digest = md.digest(this.toByteArray())
        val hexString = digest.joinToString("") { "%02x".format(it) }
        return hexString
    }

    private fun withMemberUser(status: String, block: () -> Unit) {
        this.connection.execute(
            """
            INSERT INTO users (id, email, password, role, member_number, civic_center_id, status)
            VALUES (
                '$MEMBER_ID', 
                'member@test.com', 
                '${"myPassword".md5()}', 
                'member', 
                '123456', 
                '$CIVIC_CENTER_ID', 
                '$status'
            )
            """.trimIndent()
        )

        block()
    }
}
