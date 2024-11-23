package advertisement.domain.exceptions

import common.domain.DomainException

class InvalidUniqueIdentifierException private constructor(message: String) : DomainException(message) {

    companion object {
        fun withId(id: String): InvalidUniqueIdentifierException {
            return InvalidUniqueIdentifierException("Invalid unique identifier format for $id")
        }
    }
}
