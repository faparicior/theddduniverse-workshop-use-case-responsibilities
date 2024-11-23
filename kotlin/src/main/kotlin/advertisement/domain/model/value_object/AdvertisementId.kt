package advertisement.domain.model.value_object

import advertisement.domain.exceptions.InvalidUniqueIdentifierException

class AdvertisementId(private var value: String) {

    init {
        this.validate(value)
    }

    fun value(): String {
        return value
    }

    private fun validate(value: String) {
        val regex = "^[0-9a-fA-F]{8}-[0-9a-fA-F]{4}-4[0-9a-fA-F]{3}-[0-9a-fA-F]{4}-[0-9a-fA-F]{12}$"

        if (!value.matches(regex.toRegex())) {
            throw InvalidUniqueIdentifierException.withId(value)
        }
    }
}
