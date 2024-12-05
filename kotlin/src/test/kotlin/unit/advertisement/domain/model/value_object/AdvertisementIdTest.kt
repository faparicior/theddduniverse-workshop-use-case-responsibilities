package unit.advertisement.domain.model.value_object

import advertisements.advertisement.domain.value_object.AdvertisementId
import advertisements.shared.exceptions.InvalidUniqueIdentifierException
import org.junit.jupiter.api.Assertions
import org.junit.jupiter.api.Test


class AdvertisementIdTest
{
    companion object {
        private const val ID = "6fa00b21-2930-483e-b610-d6b0e5b19b29"
        private const val INVALID_ID = "6fa00b21-2930-983e-b610-d6b0e5b19b29"
    }

    @Test
    fun testShouldCreateADescription() {
        val description = AdvertisementId(ID)

        Assertions.assertEquals(ID, description.value())
    }

    @Test
    fun testShouldThrowAnExceptionWhenIdHasNotUuidV4Standards() {
        Assertions.assertThrows(InvalidUniqueIdentifierException::class.java) {
            AdvertisementId(INVALID_ID)
        }
    }
}
