package advertisements.user.domain.exceptions

import common.domain.DomainException

class UserNotFoundException private constructor(message: String) : DomainException(message) {

    companion object {
        private const val ADMIN_USER_NOT_FOUND_MESSAGE = "Admin user not found"
        private const val MEMBER_USER_NOT_FOUND_MESSAGE = "Member user not found"

        fun asAdmin(): UserNotFoundException {
            return UserNotFoundException(ADMIN_USER_NOT_FOUND_MESSAGE)
        }

        fun asMember(): UserNotFoundException {
            return UserNotFoundException(MEMBER_USER_NOT_FOUND_MESSAGE)
        }
    }

    fun message(): String {
        return this.message ?: ""
    }
}
