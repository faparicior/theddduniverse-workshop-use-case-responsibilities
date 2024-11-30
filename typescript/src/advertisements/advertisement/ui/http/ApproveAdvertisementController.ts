import { FrameworkRequest } from '../../../../framework/FrameworkRequest';
import { FrameworkResponse } from '../../../../framework/FrameworkResponse';
import {CommonController} from "../../../../common/ui/CommonController";
import {BoundedContextException} from "../../../../common/exceptions/BoundedContextException";
import {FrameworkSecurityService} from "../../../../framework/security-user/FrameworkSecurityService";
import {ApproveAdvertisementCommand} from "../../application/command/approve-advertisement/ApproveAdvertisementCommand";
import {ApproveAdvertisementUseCase} from "../../application/command/approve-advertisement/ApproveAdvertisementUseCase";

type AddAdvertisementRequest = FrameworkRequest & {
  body: {
    id: string;
    description: string;
    password: string;
  };
};

export class ApproveAdvertisementController extends CommonController {

  constructor(
    private approveAdvertisementUseCase: ApproveAdvertisementUseCase,
    private frameworkSecurityService: FrameworkSecurityService,
  ) {
    super();
  }
  async execute(req: AddAdvertisementRequest, params: Record<string, any> = {}): Promise<FrameworkResponse> {
    try {
      let user = await this.frameworkSecurityService.getSecurityUserFromRequest(req)

      if (user === null || user.role() !== 'admin') {
        return this.processUnauthorizedResponse();
      }

      const command = new ApproveAdvertisementCommand(
        user.id(),
        user.role(),
        params.advertisementId,
      )

      await this.approveAdvertisementUseCase.execute(command)

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
