package advertisements.advertisement.application.command.disableadvertisement

data class DisableAdvertisementCommand(
    val securityUserId: String,
    val securityUserRole: String,
    val advertisementId: String,
)
