package advertisements.user.domain

import advertisements.shared.value_object.Email
import advertisements.shared.value_object.Password
import advertisements.shared.value_object.UserId
import advertisements.user.domain.value_object.Role
import advertisements.user.domain.value_object.UserStatus

abstract class UserBase(
    protected val id: UserId,
    protected val email: Email,
    protected val role: Role,
    protected var status: UserStatus
) {
    protected var password: Password? = null

    fun id(): UserId {
        return id
    }

    fun email(): Email {
        return email
    }

    fun password(): Password? {
        return password
    }

    fun role(): Role {
        return role
    }

    fun status(): UserStatus {
        return status
    }
}