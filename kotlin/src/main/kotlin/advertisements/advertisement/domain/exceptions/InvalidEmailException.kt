package advertisements.advertisement.domain.exceptions

import common.domain.DomainException

class InvalidEmailException private constructor(message: String) : DomainException(message) {

    companion object {
        fun withEmail(email: String): InvalidEmailException {
            return InvalidEmailException("Invalid email format $email")
        }
    }
}
