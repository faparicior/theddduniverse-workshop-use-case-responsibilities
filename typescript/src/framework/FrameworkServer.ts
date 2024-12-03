import {PublishAdvertisementController} from "../advertisements/advertisement/ui/http/PublishAdvertisementController";
import {
  PublishAdvertisementUseCase
} from "../advertisements/advertisement/application/command/publish-advertisement/PublishAdvertisementUseCase";
import {
  SqliteAdvertisementRepository
} from "../advertisements/advertisement/infrastructure/persistence/SqliteAdvertisementRepository";
import {FrameworkRequest, Method} from "./FrameworkRequest";
import {FrameworkResponse} from "./FrameworkResponse";
import {SqliteConnectionFactory} from "./database/SqliteConnectionFactory";
import {
  UpdateAdvertisementUseCase
} from "../advertisements/advertisement/application/command/update-advertisement/UpdateAdvertisementUseCase";
import {UpdateAdvertisementController} from "../advertisements/advertisement/ui/http/UpdateAdvertisementController";
import {RenewAdvertisementController} from "../advertisements/advertisement/ui/http/RenewAdvertisementController";
import {
  RenewAdvertisementUseCase
} from "../advertisements/advertisement/application/command/renew-advertisement/RenewAdvertisementUseCase";
import {FrameworkSecurityService} from "./security-user/FrameworkSecurityService";
import {SqliteSecurityUserRepository} from "./security-user/SqliteSecurityUserRepository";
import {SqliteUserRepository} from "../advertisements/user/infrastructure/persistence/SqliteUserRepository";
import {SignUpMemberController} from "../advertisements/user/ui/http/SignUpMemberController";
import {SignUpMemberUseCase} from "../advertisements/user/application/command/sign-up-member/SignUpMemberUseCase";
import {DeleteAdvertisementController} from "../advertisements/advertisement/ui/http/DeleteAdvertisementController";
import {
  DeleteAdvertisementUseCase
} from "../advertisements/advertisement/application/command/delete-advertisement/DeleteAdvertisementUseCase";
import {ApproveAdvertisementController} from "../advertisements/advertisement/ui/http/ApproveAdvertisementController";
import {
  ApproveAdvertisementUseCase
} from "../advertisements/advertisement/application/command/approve-advertisement/ApproveAdvertisementUseCase";
import {DisableAdvertisementController} from "../advertisements/advertisement/ui/http/DisableAdvertisementController";
import {EnableAdvertisementController} from "../advertisements/advertisement/ui/http/EnableAdvertisementController";
import {
  DisableAdvertisementUseCase
} from "../advertisements/advertisement/application/command/disable-advertisement/DisableAdvertisementUseCase";
import {
  EnableAdvertisementUseCase
} from "../advertisements/advertisement/application/command/enable-advertisement/EnableAdvertisementUseCase";
import {DisableMemberController} from "../advertisements/user/ui/http/DisableMemberController";
import {DisableMemberUseCase} from "../advertisements/user/application/command/disable-member/DisableMemberUseCase";
import {EnableMemberController} from "../advertisements/user/ui/http/EnableMemberController";
import {EnableMemberUseCase} from "../advertisements/user/application/command/enable-member/EnableMemberUseCase";
import {SecurityService} from "../advertisements/advertisement/domain/services/SecurityService";

export class FrameworkServer {

  private constructor(
    private publishAdvertisementController: PublishAdvertisementController,
    private updateAdvertisementController: UpdateAdvertisementController,
    private renewAdvertisementController: RenewAdvertisementController,
    private signUpMemberController: SignUpMemberController,
    private deleteAdvertisementController: DeleteAdvertisementController,
    private approveAdvertisementController: ApproveAdvertisementController,
    private disableAdvertisementController: DisableAdvertisementController,
    private enableAdvertisementController: EnableAdvertisementController,
    private disableMemberController: DisableMemberController,
    private enableMemberController: EnableMemberController,
  ) { };

  static async start(): Promise<FrameworkServer> {
    const connection = await SqliteConnectionFactory.createClient();
    const advertisementRepository = new SqliteAdvertisementRepository(connection);
    const userRepository = new SqliteUserRepository(connection);
    const securityService = new SecurityService(userRepository);
    const publishAdvertisementUseCase = new PublishAdvertisementUseCase(advertisementRepository, userRepository);
    const updateAdvertisementUseCase = new UpdateAdvertisementUseCase(advertisementRepository, securityService);
    const publishAdvertisementController = new PublishAdvertisementController(
      publishAdvertisementUseCase,
      new FrameworkSecurityService(
          new SqliteSecurityUserRepository(connection)
      )
    );
    const updateAdvertisementController = new UpdateAdvertisementController(
        updateAdvertisementUseCase,
        new FrameworkSecurityService(
            new SqliteSecurityUserRepository(connection)
        )
    )
    const renewAdvertisementUseCase = new RenewAdvertisementUseCase(advertisementRepository);
    const renewAdvertisementController = new RenewAdvertisementController(renewAdvertisementUseCase)

    const signUpMemberController = new SignUpMemberController(
        new SignUpMemberUseCase(userRepository),
        new FrameworkSecurityService(
            new SqliteSecurityUserRepository(connection)
        )
    )

    const deleteAdvertisementController = new DeleteAdvertisementController(
        new DeleteAdvertisementUseCase(advertisementRepository, securityService),
        new FrameworkSecurityService(
            new SqliteSecurityUserRepository(connection)
        )
    )

    const approveAdvertisementController = new ApproveAdvertisementController(
        new ApproveAdvertisementUseCase(advertisementRepository, userRepository, securityService),
        new FrameworkSecurityService(
            new SqliteSecurityUserRepository(connection)
        )
    )

    const disableAdvertisementController = new DisableAdvertisementController(
        new DisableAdvertisementUseCase(advertisementRepository, securityService),
        new FrameworkSecurityService(
            new SqliteSecurityUserRepository(connection)
        )
    )

    const enableAdvertisementController = new EnableAdvertisementController(
        new EnableAdvertisementUseCase(advertisementRepository, userRepository, securityService),
        new FrameworkSecurityService(
            new SqliteSecurityUserRepository(connection)
        )
    )

    const disableMemberController = new DisableMemberController(
        new DisableMemberUseCase(userRepository, securityService),
        new FrameworkSecurityService(
            new SqliteSecurityUserRepository(connection)
        )
    )

    const enableMemberController = new EnableMemberController(
        new EnableMemberUseCase(userRepository, securityService),
        new FrameworkSecurityService(
            new SqliteSecurityUserRepository(connection)
        )
    )

    return new FrameworkServer(
        publishAdvertisementController,
        updateAdvertisementController,
        renewAdvertisementController,
        signUpMemberController,
        deleteAdvertisementController,
        approveAdvertisementController,
        disableAdvertisementController,
        enableAdvertisementController,
        disableMemberController,
        enableMemberController,
    );
  }

  public async route(request: FrameworkRequest): Promise<FrameworkResponse> {
    switch (request.method) {
      case Method.GET:
        return this.get(request);
      case Method.POST:
        return this.post(request);
      case Method.PUT:
        return this.put(request);
      case Method.PATCH:
        return this.patch(request);
      case Method.DELETE:
        return this.delete(request);
      default:
        return this.notFound(request);
    }
  }

  public async get(request: FrameworkRequest): Promise<FrameworkResponse> {
    return this.notFound(request);
  }

  public async post(request: FrameworkRequest): Promise<FrameworkResponse> {
    switch (request.path) {
      case '/advertisement':
        return await this.publishAdvertisementController.execute(request);
      case '/member/signup':
        return await this.signUpMemberController.execute(request);
      default:
        return this.notFound(request);
    }
  }

  public async put(request: FrameworkRequest): Promise<FrameworkResponse> {
    let match: FrameworkResponse | null = null;

    switch (request.pathStart()) {
      case '/advertisement':
        match = await this.updateAdvertisementController.execute(request, { advertisementId: request.getIdPath() });
        break;
      default:
        match = null;
    }

    if (match instanceof FrameworkResponse) {
      return match;
    }

    const path = request.path;
    const patterns = [
      { regex: /^\/member\/([0-9a-fA-F\-]+)\/disable$/, controller: this.disableMemberController, paramName: 'memberId' },
      { regex: /^\/member\/([0-9a-fA-F\-]+)\/enable$/, controller: this.enableMemberController, paramName: 'memberId' },
      { regex: /^\/advertisement\/([0-9a-fA-F\-]+)\/disable$/, controller: this.disableAdvertisementController, paramName: 'advertisementId' },
      { regex: /^\/advertisement\/([0-9a-fA-F\-]+)\/enable$/, controller: this.enableAdvertisementController, paramName: 'advertisementId' },
      { regex: /^\/advertisement\/([0-9a-fA-F\-]+)\/approve$/, controller: this.approveAdvertisementController, paramName: 'advertisementId' },
    ];

    for (const pattern of patterns) {
      const matches = path.match(pattern.regex);
      if (matches) {
        match = await pattern.controller.execute(request, { [pattern.paramName]: matches[1] });
        break;
      }
    }

    if (match instanceof FrameworkResponse) {
      return match;
    }

    return this.notFound(request);
  }

  public async patch(request: FrameworkRequest): Promise<FrameworkResponse> {
    switch (request.pathStart()) {
      case '/advertisement':
        return await this.renewAdvertisementController.execute(request, { advertisementId: request.getIdPath() });
      default:
        return this.notFound(request);
    }
  }

  public async delete(request: FrameworkRequest): Promise<FrameworkResponse> {
    switch (request.pathStart()) {
      case '/advertisement':
        return await this.deleteAdvertisementController.execute(request, { advertisementId: request.getIdPath() });
      default:
        return this.notFound(request);
    }
  }

  public notFound(request: FrameworkRequest): FrameworkResponse {
    return new FrameworkResponse(404, {});
  }
}
