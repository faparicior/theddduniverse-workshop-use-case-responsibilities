package advertisements.user.ui.http

import advertisements.user.application.command.signupmember.SignUpMemberCommand
import advertisements.user.application.command.signupmember.SignUpMemberUseCase
import common.application.ElementNotFoundException
import common.exceptions.BoundedContextException
import common.ui.http.CommonController
import framework.FrameworkRequest
import framework.FrameworkResponse
import framework.securityuser.FrameworkSecurityService

class SignUpMemberController(
    private val useCase: SignUpMemberUseCase,
    private val securityService: FrameworkSecurityService,
): CommonController() {

    override fun execute(request: FrameworkRequest, pathValues: Map<String, String>): FrameworkResponse {
        try {
            val user = securityService.getSecurityUserFromRequest(request)!!

            if (user.role != "admin") {
                return processUnauthorizedResponse()
            }

            useCase.execute(
                SignUpMemberCommand(
                    user.id,
                    user.role,
                    request.content["id"]!!,
                    request.content["email"]!!,
                    request.content["password"]!!,
                    request.content["memberNumber"]!!,
                    request.content["civicCenterId"]!!
                )
            )

            return processSuccessfulCreateCommand()
        } catch (e: ElementNotFoundException) {
            return processNotFoundCommand(e)
        } catch (e: BoundedContextException) {
            return processApplicationOrDomainException(e)
        } catch (e: Exception) {
            return processGenericException(e)
        }
    }
}
