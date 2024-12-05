package framework.securityuser


data class SecurityUser(
    val id: String,
    val email: String,
    val password: String,
    val role: String,
    val status: String
) {
    companion object {
        const val STATUS_ACTIVE = "active"
        const val STATUS_INACTIVE = "inactive"
    }
}