package advertisements.user.domain.exceptions

import common.domain.DomainException


class AdminWithIncorrectCivicCenterException private constructor(message: String) : DomainException(message) {

    companion object {
        private const val ADMIN_DOES_NOT_BELONG_TO_THE_SAME_CIVIC_CENTER = "Admin does not belong to the same civic center"

        fun differentCivicCenterFromMember(): AdminWithIncorrectCivicCenterException {
            return AdminWithIncorrectCivicCenterException(ADMIN_DOES_NOT_BELONG_TO_THE_SAME_CIVIC_CENTER)
        }
    }

    fun message(): String {
        return this.message ?: ""
    }
}