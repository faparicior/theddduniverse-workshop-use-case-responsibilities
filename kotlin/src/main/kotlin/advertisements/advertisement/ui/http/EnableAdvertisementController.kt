package advertisements.advertisement.ui.http

import advertisements.advertisement.application.command.enableadvertisement.EnableAdvertisementCommand
import advertisements.advertisement.application.command.enableadvertisement.EnableAdvertisementUseCase
import common.application.ElementNotFoundException
import common.exceptions.BoundedContextException
import common.ui.http.CommonController
import framework.FrameworkRequest
import framework.FrameworkResponse
import framework.securityuser.FrameworkSecurityService

class EnableAdvertisementController(
    private val useCase: EnableAdvertisementUseCase,
    private val securityService: FrameworkSecurityService,
): CommonController() {

    override fun execute(request: FrameworkRequest, pathValues: Map<String, String>): FrameworkResponse {
        try {
            val user = securityService.getSecurityUserFromRequest(request)!!

            if (user.role != "admin") {
                return processUnauthorizedResponse()
            }

            useCase.execute(
                EnableAdvertisementCommand(
                    user.id,
                    user.role,
                    pathValues["advertisementId"]!!,
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