package advertisements.user.domain.value_object

enum class Role {
    SUPERVISOR,
    ADMIN,
    MEMBER;

    companion object {
        fun fromString(role: String): Role {
            return when (role.lowercase()) {
                "supervisor" -> SUPERVISOR
                "admin" -> ADMIN
                "member" -> MEMBER
                else -> throw IllegalArgumentException("Unknown role: $role")
            }
        }
    }

    fun value(): String {
        return when (this) {
            SUPERVISOR -> "supervisor"
            ADMIN -> "admin"
            MEMBER -> "member"
        }
    }

    fun isAdmin(): Boolean {
        return this == ADMIN
    }
}
