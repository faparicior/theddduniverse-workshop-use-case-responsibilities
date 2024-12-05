package advertisements.user.application.command.enablemember

data class EnableMemberCommand (
    val securityUserId: String,
    val securityUserRole: String,
    val memberId: String,
)
