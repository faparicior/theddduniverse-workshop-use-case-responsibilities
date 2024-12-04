package advertisements.advertisement.application.command.updateadvertisement

data class UpdateAdvertisementCommand(
    val securityUserId: String,
    val securityUserRole: String,
    val id: String,
    val description: String,
    val email: String,
    val password: String
)
