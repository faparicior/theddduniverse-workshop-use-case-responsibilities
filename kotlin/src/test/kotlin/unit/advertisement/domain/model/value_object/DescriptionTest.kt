package unit.advertisement.domain.model.value_object

import advertisement.domain.exceptions.DescriptionEmptyException
import advertisement.domain.exceptions.DescriptionTooLongException
import advertisement.domain.model.value_object.Description
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
        Assertions.assertThrows(DescriptionEmptyException::class.java) {
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
