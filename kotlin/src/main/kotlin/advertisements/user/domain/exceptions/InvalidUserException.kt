package advertisements.user.domain.exceptions

import common.domain.DomainException

class InvalidUserException private constructor(message: String) : DomainException(message) {

    companion object {
        private const val INVALID_USER_MESSAGE = "Invalid user"

        fun build(message: String): InvalidUserException {
            return InvalidUserException(INVALID_USER_MESSAGE)
        }
    }

    fun message(): String {
        return this.message ?: ""
    }
}