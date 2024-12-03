import {FrameworkResponse} from "../../framework/FrameworkResponse";


export class CommonController {
    protected processSuccessfulCommand(): FrameworkResponse {
        return new FrameworkResponse(
            200,
            {
                errors: '',
                code: 200,
                message: ''
            })
    }

    protected processSuccessfulCreateCommand(): FrameworkResponse {
        return new FrameworkResponse(
            201,
            {
                errors: '',
                code: 201,
                message: ''
            })
    }

    // failed

    protected processFailedCommand(error: any): FrameworkResponse {
        return new FrameworkResponse(
            400,
            {
                errors: error.message,
                code: 400,
                message: error.message
            })
    }

    protected processDomainOrApplicationExceptionResponse(error: any): FrameworkResponse {
        let code = 400;
        if (error.code() === 404) {
            code = 404
        }

        return new FrameworkResponse(
            code,
            {
                errors: error.message,
                code: code,
                message: error.message
            })
    }

    protected processUnauthorizedResponse(): FrameworkResponse {
        return new FrameworkResponse(
            401,
            {
                errors: 'Unauthorized',
                code: 401,
                message: 'Unauthorized'
            })
    }
}