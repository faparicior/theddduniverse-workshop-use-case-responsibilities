package advertisements.advertisement.ui.http

import advertisements.advertisement.application.command.disableAdvertisement.DisableAdvertisementCommand
import advertisements.advertisement.application.command.disableAdvertisement.DisableAdvertisementUseCase
import common.application.ElementNotFoundException
import common.exceptions.BoundedContextException
import common.ui.http.CommonController
import framework.FrameworkRequest
import framework.FrameworkResponse
import framework.securityuser.FrameworkSecurityService

class DisableAdvertisementController(
    private val useCase: DisableAdvertisementUseCase,
    private val securityService: FrameworkSecurityService,
): CommonController() {

    override fun execute(request: FrameworkRequest, pathValues: Map<String, String>): FrameworkResponse {
        try {
            val user = securityService.getSecurityUserFromRequest(request)!!

            if (user.role != "admin") {
                return processUnauthorizedResponse()
            }

            useCase.execute(
                DisableAdvertisementCommand(
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