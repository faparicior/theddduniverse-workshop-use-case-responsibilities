package advertisements.user.domain

import advertisements.shared.value_object.CivicCenterId
import advertisements.shared.value_object.Email
import advertisements.shared.value_object.Password
import advertisements.shared.value_object.UserId
import advertisements.user.domain.value_object.Role
import advertisements.user.domain.value_object.UserStatus
import advertisements.user.domain.value_object.MemberNumber
import advertisements.user.domain.exceptions.InvalidUserException

class MemberUser private constructor(
    id: UserId,
    email: Email,
    role: Role,
    private val memberNumber: MemberNumber,
    private val civicCenterId: CivicCenterId,
    status: UserStatus
) : UserBase(id, email, role, status) {

    init {
        if (role != Role.MEMBER) {
            throw InvalidUserException.build("Invalid role for member user")
        }
    }

    companion object {
        @Throws(InvalidUserException::class)
        fun signUp(
            id: UserId,
            email: Email,
            password: Password,
            role: Role,
            memberNumber: MemberNumber,
            civicCenterId: CivicCenterId
        ): MemberUser {
            val member = MemberUser(id, email, role, memberNumber, civicCenterId, UserStatus.ENABLED)
            member.password = password
            return member
        }

        @Throws(InvalidUserException::class)
        fun fromDatabase(
            id: UserId,
            email: Email,
            role: Role,
            memberNumber: MemberNumber,
            civicCenterId: CivicCenterId,
            status: UserStatus
        ): MemberUser {
            return MemberUser(id, email, role, memberNumber, civicCenterId, status)
        }
    }

    fun disable() {
        status = UserStatus.DISABLED
    }

    fun enable() {
        status = UserStatus.ENABLED
    }

    fun memberNumber(): MemberNumber {
        return memberNumber
    }

    fun civicCenterId(): CivicCenterId {
        return civicCenterId
    }
}