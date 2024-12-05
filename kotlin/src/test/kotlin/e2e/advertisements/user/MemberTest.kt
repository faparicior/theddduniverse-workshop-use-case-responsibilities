package e2e.advertisements.user

import framework.DependencyInjectionResolver
import framework.FrameworkRequest
import framework.FrameworkResponse
import framework.Server
import framework.database.DatabaseConnection
import org.junit.jupiter.api.Assertions
import org.junit.jupiter.api.BeforeEach
import org.junit.jupiter.api.Test
import java.security.MessageDigest


class MemberTest {
    companion object {
        private const val MEMBER_ID = "6fa00b21-2930-483e-b610-d6b0e5b19b29"

        private const val CIVIC_CENTER_ID = "0d5a994b-1603-4c87-accc-581a59e4457c"
        private const val ADMIN_ID = "e95a8999-cb23-4fa2-9923-e3015ef30411"

        private const val HTTP_CREATED = "201"
        private const val HTTP_OK = "200"
        private const val HTTP_BAD_REQUEST = "400"
    }

    private lateinit var connection: DatabaseConnection

    @BeforeEach
    fun init() {
        this.connection = DependencyInjectionResolver().connection()
        this.connection.execute("DELETE FROM advertisements")
        this.connection.execute("DELETE FROM users")
    }

    @Test
    fun `should signup member as admin`() {
        withAdminUser() {
            val server = Server(DependencyInjectionResolver())

            val result = server.route(
                FrameworkRequest(
                    FrameworkRequest.METHOD_POST,
                    "members/signup",
                    mapOf(
                        "id" to MEMBER_ID,
                        "email" to "member@test.com",
                        "password" to "password",
                        "memberNumber" to "123456",
                        "civicCenterId" to CIVIC_CENTER_ID,
                    ),
                    mapOf(
                        "userSession" to ADMIN_ID
                    )
                )
            )

            Assertions.assertEquals(FrameworkResponse.STATUS_CREATED, result.statusCode)
            Assertions.assertEquals(successCommandResponse(HTTP_CREATED), result.content)

            val resultSet = this.connection.query("SELECT * from users where id = '${MEMBER_ID}';")

            Assertions.assertTrue(resultSet.next())
        }
    }

    @Test
    fun `should disable member as admin`() {
        withAdminUser() {
            withMemberUser() {
                val server = Server(DependencyInjectionResolver())

                val result = server.route(
                    FrameworkRequest(
                        FrameworkRequest.METHOD_PUT,
                        "members/${MEMBER_ID}/disable",
                        mapOf(),
                        mapOf(
                            "userSession" to ADMIN_ID
                        )
                    )
                )

                Assertions.assertEquals(FrameworkResponse.STATUS_OK, result.statusCode)
                Assertions.assertEquals(successCommandResponse(HTTP_OK), result.content)

                val resultSet = this.connection.query("SELECT * from users where id = '${MEMBER_ID}';")

                val member = resultSet.next()

                Assertions.assertTrue(member)
                Assertions.assertEquals("disabled", resultSet.getString("status"))
            }
        }
    }

    @Test
    fun `should enable member as admin`() {
        withAdminUser() {
            withMemberUser("disabled") {
                val server = Server(DependencyInjectionResolver())

                val result = server.route(
                    FrameworkRequest(
                        FrameworkRequest.METHOD_PUT,
                        "members/${MEMBER_ID}/enable",
                        mapOf(),
                        mapOf(
                            "userSession" to ADMIN_ID
                        )
                    )
                )

                Assertions.assertEquals(FrameworkResponse.STATUS_OK, result.statusCode)
                Assertions.assertEquals(successCommandResponse(HTTP_OK), result.content)

                val resultSet = this.connection.query("SELECT * from users where id = '${MEMBER_ID}';")

                val member = resultSet.next()

                Assertions.assertTrue(member)
                Assertions.assertEquals("enabled", resultSet.getString("status"))
            }
        }
    }

    private fun successCommandResponse(code: String = "200"): Map<String, String> {
        return mapOf(
            "errors" to "",
            "code" to code,
            "message" to ""
        )
    }

    private fun errorCommandResponse(code: String = "400", message: String): Map<String, String> {
        return mapOf(
            "errors" to message,
            "code" to code,
            "message" to message
        )
    }

    private fun notfoundCommandResponse(message: String): Map<String, String> {
        return mapOf(
            "errors" to message,
            "code" to "404",
            "message" to message
        )
    }

    private fun invalidPasswordCommandResponse(): Map<String, String> {
        return mapOf(
            "errors" to "Password does not match",
            "code" to HTTP_BAD_REQUEST,
            "message" to "Password does not match"
        )
    }

    private fun withAdminUser(block: () -> Unit) {
        this.connection.execute(
            """
            INSERT INTO users (id, email, password, role, member_number, civic_center_id, status)
            VALUES (
                '$ADMIN_ID', 
                'admin@test.com', 
                '${"myPassword".md5()}', 
                'admin', 
                '', 
                '$CIVIC_CENTER_ID', 
                'enabled'
            )
            """.trimIndent()
        )

        block()
    }

    private fun withMemberUser(status: String = "enabled", block: () -> Unit) {
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

    private fun String.md5(): String {
        val md = MessageDigest.getInstance("MD5")
        val digest = md.digest(this.toByteArray())
        val hexString = digest.joinToString("") { "%02x".format(it) }
        return hexString
    }
}
