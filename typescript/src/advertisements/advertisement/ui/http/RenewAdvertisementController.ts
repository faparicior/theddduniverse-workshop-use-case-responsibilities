import { FrameworkRequest } from '../../../../framework/FrameworkRequest';
import { FrameworkResponse } from '../../../../framework/FrameworkResponse';
import {RenewAdvertisementUseCase} from "../../application/command/renew-advertisement/RenewAdvertisementUseCase";
import {RenewAdvertisementCommand} from "../../application/command/renew-advertisement/RenewAdvertisementCommand";
import {CommonController} from "../../../../common/ui/CommonController";
import {BoundedContextException} from "../../../../common/exceptions/BoundedContextException";

type AddAdvertisementRequest = FrameworkRequest & {
  body: {
    id: string;
    description: string;
    password: string;
  };
};

export class RenewAdvertisementController extends CommonController {

  constructor(
    private renewAdvertisementUseCase: RenewAdvertisementUseCase
  ) {
    super();
  }
  async execute(req: AddAdvertisementRequest, params: Record<string, any> = {}): Promise<FrameworkResponse> {
    try {
      const command = new RenewAdvertisementCommand(
          params.advertisementId,
          req.body.password
      )

      await this.renewAdvertisementUseCase.execute(command)

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
