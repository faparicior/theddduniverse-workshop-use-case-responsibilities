package advertisements.advertisement.domain.value_object

enum class AdvertisementStatus {
    ENABLED,
    DISABLED;

    companion object {
        fun fromString(role: String): AdvertisementStatus {
            return when (role) {
                "enabled" -> ENABLED
                "disabled" -> DISABLED
                else -> throw IllegalArgumentException("Unknown role: $role")
            }
        }
    }

    fun value(): String {
        return when (this) {
            ENABLED -> "enabled"
            DISABLED -> "disabled"
        }
    }
}
