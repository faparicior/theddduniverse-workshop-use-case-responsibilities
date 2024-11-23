package advertisements.advertisement.ui.http

import advertisements.advertisement.application.updateAdvertisement.UpdateAdvertisementCommand
import advertisements.advertisement.application.updateAdvertisement.UpdateAdvertisementUseCase
import common.application.ElementNotFoundException
import common.exceptions.BoundedContextException
import common.ui.http.CommonController
import framework.FrameworkRequest
import framework.FrameworkResponse

class UpdateAdvertisementController(private val useCase: UpdateAdvertisementUseCase): CommonController(){

    fun execute(request: FrameworkRequest): FrameworkResponse {
        try {
            useCase.execute(
                UpdateAdvertisementCommand(
                    request.getIdPath(),
                    request.content["description"]!!,
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
