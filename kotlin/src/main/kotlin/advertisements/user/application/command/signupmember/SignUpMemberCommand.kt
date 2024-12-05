package advertisements.user.application.command.signupmember

data class SignUpMemberCommand (
    val securityUserId: String,
    val securityUserRole: String,
    val memberId: String,
    val email: String,
    val password: String,
    val memberNumber: String,
    val civicCenterId: String,
)
