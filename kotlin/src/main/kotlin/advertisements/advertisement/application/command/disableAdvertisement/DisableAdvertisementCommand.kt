package advertisements.advertisement.application.command.disableAdvertisement

data class DisableAdvertisementCommand(
    val securityUserId: String,
    val securityUserRole: String,
    val advertisementId: String,
)
