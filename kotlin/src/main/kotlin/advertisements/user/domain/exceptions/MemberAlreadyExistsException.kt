package advertisements.user.domain.exceptions

import common.domain.DomainException


class MemberAlreadyExistsException private constructor(message: String) : DomainException(message) {

    companion object {
        private const val MEMBER_EXISTS_MESSAGE = "Member already exists"

        fun build(): MemberAlreadyExistsException {
            return MemberAlreadyExistsException(MEMBER_EXISTS_MESSAGE)
        }
    }

    fun message(): String {
        return this.message ?: ""
    }
}