package advertisements.advertisement.application.publishAdvertisement

data class PublishAdvertisementCommand(
    val securityUserId: String,
    val securityUserRole: String,
    val id: String,
    val description: String,
    val email: String,
    val password: String,
    val memberNumber: String,
    val civicCenterId: String
)
