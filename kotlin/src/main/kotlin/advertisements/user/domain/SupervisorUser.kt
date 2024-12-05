package advertisements.user.domain

import advertisements.shared.value_object.Email
import advertisements.shared.value_object.UserId
import advertisements.user.domain.value_object.Role
import advertisements.user.domain.value_object.UserStatus
import advertisements.user.domain.exceptions.InvalidUserException

class SupervisorUser private constructor(
    id: UserId,
    email: Email,
    role: Role,
    status: UserStatus
) : UserBase(id, email, role, status) {

    init {
        if (role != Role.SUPERVISOR) {
            throw InvalidUserException.build("Invalid role for supervisor user")
        }
    }

    companion object {
        @Throws(InvalidUserException::class)
        fun fromDatabase(id: UserId, email: Email, role: Role, status: UserStatus): SupervisorUser {
            return SupervisorUser(id, email, role, status)
        }
    }
}