package advertisements.user.domain.exceptions

import common.domain.DomainException

class UnauthorizedUserException private constructor(message: String) : DomainException(message) {

    companion object {
        private const val USER_UNAUTHORIZED = "User unauthorized"

        fun build(): UnauthorizedUserException {
            return UnauthorizedUserException(USER_UNAUTHORIZED)
        }
    }

    fun message(): String {
        return this.message ?: ""
    }
}
