package advertisements.advertisement.application.command.deleteadvertisement

data class DeleteAdvertisementCommand(
    val securityUserId: String,
    val securityUserRole: String,
    val advertisementId: String,
)
