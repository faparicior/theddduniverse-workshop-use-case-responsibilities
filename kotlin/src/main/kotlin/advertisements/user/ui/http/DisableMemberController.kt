package advertisements.user.ui.http

import advertisements.user.application.command.disablemember.DisableMemberCommand
import advertisements.user.application.command.disablemember.DisableMemberUseCase
import common.application.ElementNotFoundException
import common.exceptions.BoundedContextException
import common.ui.http.CommonController
import framework.FrameworkRequest
import framework.FrameworkResponse
import framework.securityuser.FrameworkSecurityService

class DisableMemberController(
    private val useCase: DisableMemberUseCase,
    private val securityService: FrameworkSecurityService,
): CommonController() {

    override fun execute(request: FrameworkRequest, pathValues: Map<String, String>): FrameworkResponse {
        try {
            val user = securityService.getSecurityUserFromRequest(request)!!

            if (user.role != "admin") {
                return processUnauthorizedResponse()
            }

            useCase.execute(
                DisableMemberCommand(
                    user.id,
                    user.role,
                    pathValues["memberId"]!!,
                )
            )

            return processSuccessfulCommand()
        } catch (e: ElementNotFoundException) {
            return processNotFoundCommand(e)
        } catch (e: BoundedContextException) {
            return processApplicationOrDomainException(e)
        } catch (e: Exception) {
            return processGenericException(e)
        }
    }
}
