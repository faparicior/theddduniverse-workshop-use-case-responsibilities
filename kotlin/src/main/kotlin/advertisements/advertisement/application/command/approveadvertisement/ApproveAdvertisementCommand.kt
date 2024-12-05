package advertisements.advertisement.application.command.approveadvertisement

data class ApproveAdvertisementCommand(
    val securityUserId: String,
    val securityUserRole: String,
    val advertisementId: String,
)
