package advertisement.ui.http

import advertisement.application.publishAdvertisement.PublishAdvertisementCommand
import advertisement.application.publishAdvertisement.PublishAdvertisementUseCase
import common.exceptions.BoundedContextException
import common.ui.http.CommonController
import framework.FrameworkRequest
import framework.FrameworkResponse

class PublishAdvertisementController(private val useCase: PublishAdvertisementUseCase): CommonController(){

    fun execute(request: FrameworkRequest): FrameworkResponse {
        try {
            useCase.execute(
                PublishAdvertisementCommand(
                    request.content["id"]!!,
                    request.content["description"]!!,
                    request.content["password"]!!,
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
