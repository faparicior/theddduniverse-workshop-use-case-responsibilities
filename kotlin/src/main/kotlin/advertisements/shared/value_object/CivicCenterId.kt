package advertisements.shared.value_object

import advertisements.shared.exceptions.InvalidUniqueIdentifierException

class CivicCenterId private constructor(private val value: String) {

    init {
        if (!validate(value)) {
            throw InvalidUniqueIdentifierException.withId(value)
        }
    }

    fun value(): String {
        return value
    }

    fun equals(id: CivicCenterId): Boolean {
        return this.value == id.value
    }

    private fun validate(value: String): Boolean {
        val regex = Regex("^[0-9A-F]{8}-[0-9A-F]{4}-4[0-9A-F]{3}-[89AB][0-9A-F]{3}-[0-9A-F]{12}$", RegexOption.IGNORE_CASE)
        return regex.matches(value)
    }

    companion object {
        @Throws(InvalidUniqueIdentifierException::class)
        fun create(value: String): CivicCenterId {
            return CivicCenterId(value)
        }
    }
}