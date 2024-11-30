import { FrameworkRequest } from '../../../../framework/FrameworkRequest';
import { FrameworkResponse } from '../../../../framework/FrameworkResponse';
import {RenewAdvertisementUseCase} from "../../application/command/renew-advertisement/RenewAdvertisementUseCase";
import {RenewAdvertisementCommand} from "../../application/command/renew-advertisement/RenewAdvertisementCommand";
import {CommonController} from "../../../../common/ui/CommonController";
import {BoundedContextException} from "../../../../common/exceptions/BoundedContextException";
import {DeleteAdvertisementUseCase} from "../../application/command/delete-advertisement/DeleteAdvertisementUseCase";
import {DeleteAdvertisementCommand} from "../../application/command/delete-advertisement/DeleteAdvertisementCommand";
import {FrameworkSecurityService} from "../../../../framework/security-user/FrameworkSecurityService";

type AddAdvertisementRequest = FrameworkRequest & {
  body: {
    id: string;
    description: string;
    password: string;
  };
};

export class DeleteAdvertisementController extends CommonController {

  constructor(
    private deleteAdvertisementUseCase: DeleteAdvertisementUseCase,
    private frameworkSecurityService: FrameworkSecurityService,
  ) {
    super();
  }
  async execute(req: AddAdvertisementRequest, params: Record<string, any> = {}): Promise<FrameworkResponse> {
    try {
      let user = await this.frameworkSecurityService.getSecurityUserFromRequest(req)

      if (user === null || user.role() !== 'member') {
        return this.processUnauthorizedResponse();
      }

      const command = new DeleteAdvertisementCommand(
        user.id(),
        user.role(),
        params.advertisementId,
      )

      await this.deleteAdvertisementUseCase.execute(command)

      return this.processSuccessfulCommand()
    } catch (error: any) {
      switch (true) {
        case error instanceof BoundedContextException:
          return this.processDomainOrApplicationExceptionResponse(error)
        default:
          return this.processFailedCommand(error)
      }
    }
  }
}
