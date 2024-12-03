import { FrameworkRequest } from '../../../../framework/FrameworkRequest';
import { FrameworkResponse } from '../../../../framework/FrameworkResponse';
import {CommonController} from "../../../../common/ui/CommonController";
import {BoundedContextException} from "../../../../common/exceptions/BoundedContextException";
import {FrameworkSecurityService} from "../../../../framework/security-user/FrameworkSecurityService";
import {EnableAdvertisementCommand} from "../../application/command/enable-advertisement/EnableAdvertisementCommand";
import {EnableAdvertisementUseCase} from "../../application/command/enable-advertisement/EnableAdvertisementUseCase";

type AddAdvertisementRequest = FrameworkRequest & {
  body: {
    id: string;
    description: string;
    password: string;
  };
};

export class EnableAdvertisementController extends CommonController {

  constructor(
    private enableAdvertisementUseCase: EnableAdvertisementUseCase,
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

      const command = new EnableAdvertisementCommand(
        user.id(),
        user.role(),
        params.advertisementId,
      )

      await this.enableAdvertisementUseCase.execute(command)

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
