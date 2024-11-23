package advertisements.advertisement.ui.http

import advertisements.advertisement.application.command.renewAdvertisement.RenewAdvertisementCommand
import advertisements.advertisement.application.command.renewAdvertisement.RenewAdvertisementUseCase
import common.application.ElementNotFoundException
import common.exceptions.BoundedContextException
import common.ui.http.CommonController
import framework.FrameworkRequest
import framework.FrameworkResponse

class RenewAdvertisementController(private val useCase: RenewAdvertisementUseCase): CommonController() {

    fun execute(request: FrameworkRequest): FrameworkResponse {
        try {
            useCase.execute(
                RenewAdvertisementCommand(
                    request.getIdPath(),
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