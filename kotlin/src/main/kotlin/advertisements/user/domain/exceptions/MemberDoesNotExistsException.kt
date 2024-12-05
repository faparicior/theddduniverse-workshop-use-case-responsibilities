package advertisements.user.domain.exceptions

import common.domain.DomainException

class MemberDoesNotExistsException private constructor(message: String) : DomainException(message) {

    companion object {
        private const val MEMBER_DOES_NOT_EXISTS_MESSAGE = "Member does not exists"

        fun build(): MemberDoesNotExistsException {
            return MemberDoesNotExistsException(MEMBER_DOES_NOT_EXISTS_MESSAGE)
        }
    }

    fun message(): String {
        return this.message ?: ""
    }
}