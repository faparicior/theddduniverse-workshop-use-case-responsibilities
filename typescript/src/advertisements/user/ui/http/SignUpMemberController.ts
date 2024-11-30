import { FrameworkRequest } from '../../../../framework/FrameworkRequest';
import { FrameworkResponse } from '../../../../framework/FrameworkResponse';
import {CommonController} from "../../../../common/ui/CommonController";
import {BoundedContextException} from "../../../../common/exceptions/BoundedContextException";
import {FrameworkSecurityService} from "../../../../framework/security-user/FrameworkSecurityService";
import {SignUpMemberUseCase} from "../../application/command/sign-up-member/SignUpMemberUseCase";
import {SignUpMemberCommand} from "../../application/command/sign-up-member/SignUpMemberCommand";

type AddAdvertisementRequest = FrameworkRequest & {
  body: {
    id: string;
    description: string;
    password: string;
  };
};

export class SignUpMemberController extends CommonController {

  constructor(
    private signUpMemberUseCase: SignUpMemberUseCase,
    private frameworkSecurityService: FrameworkSecurityService,
) {
    super();
  }
  async execute(req: AddAdvertisementRequest): Promise<FrameworkResponse> {

    try {
      let user = await this.frameworkSecurityService.getSecurityUserFromRequest(req)

      if (user === null || user.role() !== 'admin') {
        return this.processUnauthorizedResponse();
      }

      const command = new SignUpMemberCommand(
        user.id(),
        user.role(),
        req.body.id,
        req.body.email,
        req.body.password,
        req.body.memberNumber,
        req.body.civicCenterId,
      )

      await this.signUpMemberUseCase.execute(command)

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
