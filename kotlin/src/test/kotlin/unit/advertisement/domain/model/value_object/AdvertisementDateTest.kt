package unit.advertisement.domain.model.value_object

import advertisements.advertisement.domain.value_object.AdvertisementDate
import org.junit.jupiter.api.Assertions
import org.junit.jupiter.api.Test
import java.time.LocalDateTime


class AdvertisementDateTest
{
    @Test
    fun testShouldCreateADate() {
        val dateNow = LocalDateTime.now()

        val advertisementDate = AdvertisementDate(dateNow)

        Assertions.assertEquals(dateNow, advertisementDate.value())
    }
}
