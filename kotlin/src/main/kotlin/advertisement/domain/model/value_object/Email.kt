package advertisement.domain.model.value_object

import advertisement.domain.exceptions.InvalidEmailException

class Email(private var value: String) {

    init {
        this.validate(value)
    }

    fun value(): String {
        return value
    }

    private fun validate(value: String) {
        val regex = "^[\\w-\\.]+@([\\w-]+\\.)+[\\w-]{2,4}\$"

        if (!value.matches(regex.toRegex())) {
            throw InvalidEmailException.withEmail(value)
        }
    }
}
