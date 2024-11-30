import { FrameworkRequest } from '../../../../framework/FrameworkRequest';
import { FrameworkResponse } from '../../../../framework/FrameworkResponse';
import { UpdateAdvertisementCommand } from '../../application/command/update-advertisement/UpdateAdvertisementCommand';
import { UpdateAdvertisementUseCase } from '../../application/command/update-advertisement/UpdateAdvertisementUseCase';
import {CommonController} from "../../../../common/ui/CommonController";
import {BoundedContextException} from "../../../../common/exceptions/BoundedContextException";
import {FrameworkSecurityService} from "../../../../framework/security-user/FrameworkSecurityService";

type AddAdvertisementRequest = FrameworkRequest & {
  body: {
    id: string;
    description: string;
    password: string;
  };
};

export class UpdateAdvertisementController extends CommonController {

  constructor(
    private updateAdvertisementUseCase: UpdateAdvertisementUseCase,
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

      const command = new UpdateAdvertisementCommand(
          user.id(),
          user.role(),
          params.advertisementId,
          req.body.description,
          req.body.email,
          req.body.password
      )

      await this.updateAdvertisementUseCase.execute(command)

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
