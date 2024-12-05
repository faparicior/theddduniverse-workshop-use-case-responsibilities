package advertisements.user.domain

import advertisements.shared.value_object.CivicCenterId
import advertisements.shared.value_object.Email
import advertisements.shared.value_object.UserId
import advertisements.user.domain.value_object.Role
import advertisements.user.domain.value_object.UserStatus
import advertisements.user.domain.exceptions.InvalidUserException

class AdminUser private constructor(
    id: UserId,
    email: Email,
    role: Role,
    private val civicCenterId: CivicCenterId,
    status: UserStatus
) : UserBase(id, email, role, status) {

    init {
        if (role != Role.ADMIN) {
            throw InvalidUserException.build("Invalid role for admin user")
        }
    }

    companion object {
        @Throws(InvalidUserException::class)
        fun fromDatabase(
            id: UserId,
            email: Email,
            role: Role,
            civicCenterId: CivicCenterId,
            status: UserStatus
        ): AdminUser {
            return AdminUser(id, email, role, civicCenterId, status)
        }
    }

    fun civicCenterId(): CivicCenterId {
        return civicCenterId
    }
}
