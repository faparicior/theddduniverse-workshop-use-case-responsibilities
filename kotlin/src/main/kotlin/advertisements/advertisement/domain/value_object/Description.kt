package advertisements.advertisement.domain.value_object

import advertisements.advertisement.domain.exceptions.DescriptionEmptyException
import advertisements.advertisement.domain.exceptions.DescriptionTooLongException

class Description(private var value: String) {

    init {
        this.validate(value)
    }

    fun value(): String {
        return value
    }

    private fun validate(value: String) {
        if (value.isEmpty()) {
            throw advertisements.advertisement.domain.exceptions.DescriptionEmptyException.build()
        }

        if (value.length > 200) {
            throw DescriptionTooLongException.withLongitudeMessage(value)
        }
    }
}
