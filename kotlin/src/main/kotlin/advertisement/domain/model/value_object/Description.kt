package advertisement.domain.model.value_object

import advertisement.domain.exceptions.DescriptionEmptyException
import advertisement.domain.exceptions.DescriptionTooLongException

class Description(private var value: String) {

    init {
        this.validate(value)
    }

    fun value(): String {
        return value
    }

    private fun validate(value: String) {
        if (value.isEmpty()) {
            throw DescriptionEmptyException.build()
        }

        if (value.length > 200) {
            throw DescriptionTooLongException.withLongitudeMessage(value)
        }
    }
}
