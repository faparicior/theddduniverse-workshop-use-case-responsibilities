package advertisements.advertisement.ui.http

import advertisements.advertisement.application.command.publishAdvertisement.PublishAdvertisementCommand
import advertisements.advertisement.application.command.publishAdvertisement.PublishAdvertisementUseCase
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
