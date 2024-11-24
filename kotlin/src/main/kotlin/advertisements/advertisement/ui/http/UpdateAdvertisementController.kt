package advertisements.advertisement.ui.http

import advertisements.advertisement.application.command.updateAdvertisement.UpdateAdvertisementCommand
import advertisements.advertisement.application.command.updateAdvertisement.UpdateAdvertisementUseCase
import common.application.ElementNotFoundException
import common.exceptions.BoundedContextException
import common.ui.http.CommonController
import framework.FrameworkRequest
import framework.FrameworkResponse
import framework.securityuser.FrameworkSecurityService

class UpdateAdvertisementController(
    private val useCase: UpdateAdvertisementUseCase,
    private val securityService: FrameworkSecurityService,
): CommonController(){

    fun execute(request: FrameworkRequest): FrameworkResponse {
        try {
            val user = securityService.getSecurityUserFromRequest(request)

            if (user?.role != "ADMIN") {
                return processForbiddenException()
            }

            useCase.execute(
                UpdateAdvertisementCommand(
                    user.id,
                    user.role,
                    request.getIdPath(),
                    request.content["description"]!!,
                    request.content["email"]!!,
                    request.content["password"]!!,
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
