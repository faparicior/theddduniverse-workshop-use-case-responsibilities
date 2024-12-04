package advertisements.user.application.command.disablemember

data class DisableMemberCommand (
    val securityUserId: String,
    val securityUserRole: String,
    val memberId: String,
)
