package advertisements.advertisement.ui.http

import advertisements.advertisement.application.command.publishAdvertisement.PublishAdvertisementCommand
import advertisements.advertisement.application.command.publishAdvertisement.PublishAdvertisementUseCase
import common.exceptions.BoundedContextException
import common.ui.http.CommonController
import framework.FrameworkRequest
import framework.FrameworkResponse
import framework.securityuser.FrameworkSecurityService

class PublishAdvertisementController(
    private val useCase: PublishAdvertisementUseCase,
    private val securityService: FrameworkSecurityService,
): CommonController(){

    override fun execute(request: FrameworkRequest, pathValues: Map<String, String>): FrameworkResponse {
        try {
            val user = securityService.getSecurityUserFromRequest(request)

            if (user?.role != "member") {
                return processUnauthorizedResponse()
            }

            useCase.execute(
                PublishAdvertisementCommand(
                    user.id,
                    user.role,
                    request.content["id"]!!,
                    request.content["description"]!!,
                    request.content["email"]!!,
                    request.content["password"]!!,
                    request.content["memberId"]!!,
                    request.content["civicCenterId"]!!
                )
            )

            return processSuccessfulCreateCommand()
        } catch (e: BoundedContextException) {
            return processApplicationOrDomainException(e)
        } catch (e: Exception) {
            return processGenericException(e)
        }
    }
}
