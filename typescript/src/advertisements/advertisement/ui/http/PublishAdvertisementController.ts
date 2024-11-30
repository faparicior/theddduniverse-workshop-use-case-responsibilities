import { FrameworkRequest } from '../../../../framework/FrameworkRequest';
import { FrameworkResponse } from '../../../../framework/FrameworkResponse';
import { PublishAdvertisementCommand } from '../../application/command/publish-advertisement/PublishAdvertisementCommand';
import { PublishAdvertisementUseCase } from '../../application/command/publish-advertisement/PublishAdvertisementUseCase';
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

export class PublishAdvertisementController extends CommonController {

  constructor(
    private publishAdvertisementUseCase: PublishAdvertisementUseCase,
    private frameworkSecurityService: FrameworkSecurityService,
) {
    super();
  }
  async execute(req: AddAdvertisementRequest): Promise<FrameworkResponse> {

    try {
      let user = await this.frameworkSecurityService.getSecurityUserFromRequest(req)

      if (user === null || user.role() !== 'member') {
        return this.processUnauthorizedResponse();
      }

      const command = new PublishAdvertisementCommand(
        user.id(),
        user.role(),
        req.body.id,
        req.body.description,
        req.body.email,
        req.body.password,
        req.body.memberNumber,
        req.body.civicCenterId,
      )

      await this.publishAdvertisementUseCase.execute(command)

      return this.processSuccessfulCreateCommand()
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
