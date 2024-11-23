package advertisements.civiccenter.domain.value_object

import advertisements.advertisement.domain.exceptions.DescriptionEmptyException
import advertisements.advertisement.domain.exceptions.DescriptionTooLongException

class CivicCenterName(private val value: String) {

    init {
        validate(value)
    }

    fun value(): String {
        return value
    }

    @Throws(DescriptionEmptyException::class, DescriptionTooLongException::class)
    private fun validate(value: String) {
        if (value.isEmpty()) {
            throw DescriptionEmptyException.build()
        }

        if (value.length > 200) {
            throw DescriptionTooLongException.withLongitudeMessage(value)
        }
    }
}
