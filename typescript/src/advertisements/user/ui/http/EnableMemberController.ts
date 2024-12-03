import { FrameworkRequest } from '../../../../framework/FrameworkRequest';
import { FrameworkResponse } from '../../../../framework/FrameworkResponse';
import {CommonController} from "../../../../common/ui/CommonController";
import {BoundedContextException} from "../../../../common/exceptions/BoundedContextException";
import {FrameworkSecurityService} from "../../../../framework/security-user/FrameworkSecurityService";
import {DisableMemberCommand} from "../../application/command/disable-member/DisableMemberCommand";
import {EnableMemberUseCase} from "../../application/command/enable-member/EnableMemberUseCase";

type AddAdvertisementRequest = FrameworkRequest & {
  body: {
    id: string;
    description: string;
    password: string;
  };
};

export class EnableMemberController extends CommonController {

  constructor(
    private enableMemberUseCase: EnableMemberUseCase,
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

      const command = new DisableMemberCommand(
        user.id(),
        user.role(),
        params.memberId,
      )

      await this.enableMemberUseCase.execute(command)

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
