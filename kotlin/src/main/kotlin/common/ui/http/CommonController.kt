package common.ui.http

import framework.FrameworkRequest
import framework.FrameworkResponse

abstract class CommonController {

    open fun execute(request: FrameworkRequest, pathValues: Map<String, String> = emptyMap()): FrameworkResponse {
        return processNotFoundCommand(Exception("Not found"))
    }

    protected fun processGenericException(exception: Throwable): FrameworkResponse {
        return FrameworkResponse(
            FrameworkResponse.STATUS_INTERNAL_SERVER_ERROR,
            mapOf(
                "errors" to exception.message.toString(),
                "code" to FrameworkResponse.STATUS_BAD_REQUEST.toString(),
                "message" to exception.message.toString(),
            ),
        )
    }

    protected fun processApplicationOrDomainException(exception: Throwable): FrameworkResponse {
        return FrameworkResponse(
            FrameworkResponse.STATUS_BAD_REQUEST,
            mapOf(
                "errors" to exception.message.toString(),
                "code" to FrameworkResponse.STATUS_BAD_REQUEST.toString(),
                "message" to exception.message.toString(),
            ),
        )
    }

    protected fun processSuccessfulCreateCommand(): FrameworkResponse {
        return FrameworkResponse(
            FrameworkResponse.STATUS_CREATED,
            mapOf(
                "errors" to "",
                "code" to FrameworkResponse.STATUS_CREATED.toString(),
                "message" to "",
            ),
        )
    }

    protected fun processSuccessfulCommand(): FrameworkResponse {
        return FrameworkResponse(
            FrameworkResponse.STATUS_OK,
            mapOf(
                "errors" to "",
                "code" to FrameworkResponse.STATUS_OK.toString(),
                "message" to "",
            ),
        )
    }

    protected fun processNotFoundCommand(exception: Throwable): FrameworkResponse {
        val message = exception.message.toString()

        return FrameworkResponse(
            FrameworkResponse.STATUS_NOT_FOUND,
            mapOf(
                "errors" to message,
                "code" to FrameworkResponse.STATUS_NOT_FOUND.toString(),
                "message" to message,
            ),
        )
    }

    protected fun processUnauthorizedResponse(): FrameworkResponse {
        return FrameworkResponse(
            FrameworkResponse.STATUS_UNAUTHORIZED,
            mapOf(
                "errors" to "Forbidden",
                "code" to FrameworkResponse.STATUS_UNAUTHORIZED.toString(),
                "message" to "Forbidden",
            ),
        )
    }
}
