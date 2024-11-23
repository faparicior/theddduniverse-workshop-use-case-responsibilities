package advertisements.shared.exceptions

import common.domain.DomainException

class InvalidUniqueIdentifierException private constructor(message: String) : DomainException(message) {

    companion object {
        private const val INVALID_ID_FORMAT_WITH_ID_MESSAGE = "Invalid unique identifier format for "

        fun withId(id: String): InvalidUniqueIdentifierException {
            return InvalidUniqueIdentifierException(INVALID_ID_FORMAT_WITH_ID_MESSAGE + id)
        }
    }

    fun message(): String {
        return this.message ?: ""
    }
}
