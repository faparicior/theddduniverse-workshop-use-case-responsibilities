package advertisements.advertisement.application.command.enableadvertisement

data class EnableAdvertisementCommand(
    val securityUserId: String,
    val securityUserRole: String,
    val advertisementId: String,
)
