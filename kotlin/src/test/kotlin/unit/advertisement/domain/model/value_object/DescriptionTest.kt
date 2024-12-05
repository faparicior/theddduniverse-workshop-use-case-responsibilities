package unit.advertisement.domain.model.value_object

import advertisements.advertisement.domain.exceptions.DescriptionTooLongException
import advertisements.advertisement.domain.value_object.Description
import org.junit.jupiter.api.Assertions
import org.junit.jupiter.api.Test


class DescriptionTest
{
    companion object {
        private const val VALID_DESCRIPTION = "Description test"
    }

    @Test
    fun testShouldCreateADescription() {
        val description = Description(VALID_DESCRIPTION)

        Assertions.assertEquals(VALID_DESCRIPTION, description.value())
    }

    @Test
    fun testShouldThrowAnExceptionWhenDescriptionIsEmpty() {
        Assertions.assertThrows(advertisements.advertisement.domain.exceptions.DescriptionEmptyException::class.java) {
            Description("")
        }
    }

    @Test
    fun testShouldThrowAnExceptionWhenDescriptionIsTooLong() {
        Assertions.assertThrows(DescriptionTooLongException::class.java) {
            val longDescription = "a".repeat(201)
            Description(longDescription)
        }
    }
}
