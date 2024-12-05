package advertisements.user.domain.value_object

enum class UserStatus {
    ENABLED,
    DISABLED;

    companion object {
        fun fromString(status: String): UserStatus {
            return when (status.lowercase()) {
                "enabled" -> ENABLED
                "disabled" -> DISABLED
                else -> throw IllegalArgumentException("Invalid status: $status")
            }
        }
    }

    fun value(): String {
        return when (this) {
            ENABLED -> "enabled"
            DISABLED -> "disabled"
        }
    }

    fun isEnabled(): Boolean {
        return this == ENABLED
    }
}
