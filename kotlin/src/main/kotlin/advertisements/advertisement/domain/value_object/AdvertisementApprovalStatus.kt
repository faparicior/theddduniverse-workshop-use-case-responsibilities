package advertisements.advertisement.domain.value_object

enum class AdvertisementApprovalStatus {
    PENDING_FOR_APPROVAL,
    APPROVED;

    companion object {
        fun fromString(role: String): AdvertisementApprovalStatus {
            return when (role) {
                "pending_for_approval" -> PENDING_FOR_APPROVAL
                "approved" -> APPROVED
                else -> throw IllegalArgumentException("Unknown role: $role")
            }
        }
    }

    fun value(): String {
        return when (this) {
            PENDING_FOR_APPROVAL -> "pending_for_approval"
            APPROVED -> "approved"
        }
    }
}
