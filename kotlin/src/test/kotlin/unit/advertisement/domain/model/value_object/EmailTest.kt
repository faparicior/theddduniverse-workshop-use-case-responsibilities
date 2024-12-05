package unit.advertisement.domain.model.value_object

import advertisements.advertisement.domain.exceptions.InvalidEmailException
import advertisements.shared.value_object.Email
import org.junit.jupiter.api.Assertions
import org.junit.jupiter.api.Test


class EmailTest
{
    companion object {
        private const val VALID_EMAIL = "email@test.con"
        private const val INVALID_EMAIL = "email.test.con"
    }

    @Test
    fun testShouldCreateAnEmail() {
        val email = Email(VALID_EMAIL)

        Assertions.assertEquals(VALID_EMAIL, email.value())
    }

    @Test
    fun testShouldThrowAnExceptionWhenEmailIsInvalid() {
        Assertions.assertThrows(InvalidEmailException::class.java) {
            Email(INVALID_EMAIL)
        }
    }
}
