package e2e

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


class AdvertisementsAsAdminTest {
    companion object {
        private const val ADVERTISEMENT_CREATION_DATE = "2024-03-04T13:23:15"
        private const val DESCRIPTION = "Dream advertisement"
        private const val NEW_DESCRIPTION = "Dream advertisement changed"
        private const val MEMBER_ID = "6fa00b21-2930-483e-b610-d6b0e5b19b29"
        private const val NON_EXISTENT_ADVERTISEMENT_ID = "99999999-2930-483e-b610-d6b0e5b19b29"
        private const val PASSWORD = "myPassword"
        private const val INCORRECT_PASSWORD = "myBadPassword"

        private const val CIVIC_CENTER_ID = "0d5a994b-1603-4c87-accc-581a59e4457c";
        private const val ADMIN_ID = "e95a8999-cb23-4fa2-9923-e3015ef30411";

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
    fun `should disable an advertisement as admin`() {
        withAdminUser() {
            withAnAdvertisementCreated {
                val server = Server(DependencyInjectionResolver())

                val result = server.route(
                    FrameworkRequest(
                        FrameworkRequest.METHOD_PUT,
                        "advertisements/$MEMBER_ID/disable",
                        mapOf(),
                        mapOf(
                            "userSession" to ADMIN_ID
                        )
                    )
                )

                Assertions.assertEquals(FrameworkResponse.STATUS_OK, result.statusCode)
                Assertions.assertEquals(successCommandResponse(HTTP_OK), result.content)

                val resultSet = this.connection.query("SELECT * from advertisements;")
                var status = ""

                if (resultSet.next()) {
                    status = resultSet.getString("status")
                }

                Assertions.assertEquals("DISABLED", status)
            }
        }
    }

    @Test
    fun `should enable an advertisement as admin`() {
        withAdminUser() {
            withMemberUser {
                withAnAdvertisementCreated("disabled") {
                    val server = Server(DependencyInjectionResolver())

                    val result = server.route(
                        FrameworkRequest(
                            FrameworkRequest.METHOD_PUT,
                            "advertisements/$MEMBER_ID/enable",
                            mapOf(),
                            mapOf(
                                "userSession" to ADMIN_ID
                            )
                        )
                    )

                    Assertions.assertEquals(FrameworkResponse.STATUS_OK, result.statusCode)
                    Assertions.assertEquals(successCommandResponse(HTTP_OK), result.content)

                    val resultSet = this.connection.query("SELECT * from advertisements;")
                    var status = ""

                    if (resultSet.next()) {
                        status = resultSet.getString("status")
                    }

                    Assertions.assertEquals("ENABLED", status)
                }
            }
        }
    }

    @Test
    fun `should approve an advertisement as admin`() {
        withAdminUser() {
            withMemberUser {
                withAnAdvertisementCreated("disabled", "pending_for_approval") {
                    val server = Server(DependencyInjectionResolver())

                    val result = server.route(
                        FrameworkRequest(
                            FrameworkRequest.METHOD_PUT,
                            "advertisements/$MEMBER_ID/approve",
                            mapOf(),
                            mapOf(
                                "userSession" to ADMIN_ID
                            )
                        )
                    )

                    Assertions.assertEquals(FrameworkResponse.STATUS_OK, result.statusCode)
                    Assertions.assertEquals(successCommandResponse(HTTP_OK), result.content)

                    val resultSet = this.connection.query("SELECT * from advertisements;")
                    var status = ""

                    if (resultSet.next()) {
                        status = resultSet.getString("approval_status")
                    }

                    Assertions.assertEquals("APPROVED", status)
                }
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

    private fun withAnAdvertisementCreated(status: String = "enabled", approvalStatus: String = "approved", block: () -> Unit) {
        val password = PASSWORD.md5()
        val creationDate = LocalDateTime.parse(ADVERTISEMENT_CREATION_DATE).toString()
        this.connection.execute(
            """
            INSERT INTO advertisements (
                id, description, email, password, advertisement_date, status, approval_status, user_id, civic_center_id
            ) VALUES (
                '$MEMBER_ID', '$DESCRIPTION', 'email@test.com', '$password', '$creationDate', '$status', '$approvalStatus', '$MEMBER_ID', '$CIVIC_CENTER_ID'
            )
            """
        )

        block()
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

    private fun withMemberUser(block: () -> Unit) {
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
                'enabled'
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
