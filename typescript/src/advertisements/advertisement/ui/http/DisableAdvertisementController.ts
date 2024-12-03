import { FrameworkRequest } from '../../../../framework/FrameworkRequest';
import { FrameworkResponse } from '../../../../framework/FrameworkResponse';
import {CommonController} from "../../../../common/ui/CommonController";
import {BoundedContextException} from "../../../../common/exceptions/BoundedContextException";
import {FrameworkSecurityService} from "../../../../framework/security-user/FrameworkSecurityService";
import {DisableAdvertisementCommand} from "../../application/command/disable-advertisement/DisableAdvertisementCommand";
import {DisableAdvertisementUseCase} from "../../application/command/disable-advertisement/DisableAdvertisementUseCase";

type AddAdvertisementRequest = FrameworkRequest & {
  body: {
    id: string;
    description: string;
    password: string;
  };
};

export class DisableAdvertisementController extends CommonController {

  constructor(
    private disableAdvertisementUseCase: DisableAdvertisementUseCase,
    private frameworkSecurityService: FrameworkSecurityService,
  ) {
    super();
  }
  async execute(req: AddAdvertisementRequest, params: Record<string, any> = {}): Promise<FrameworkResponse> {
    try {
      const user = await this.frameworkSecurityService.getSecurityUserFromRequest(req)

      if (user === null || user.role() !== 'admin') {
        return this.processUnauthorizedResponse();
      }

      const command = new DisableAdvertisementCommand(
        user.id(),
        user.role(),
        params.advertisementId,
      )

      await this.disableAdvertisementUseCase.execute(command)

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
