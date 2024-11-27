import { PublishAdvertisementController } from "../advertisements/advertisement/ui/Http/PublishAdvertisementController";
import { PublishAdvertisementUseCase } from "../advertisements/advertisement/application/command/publish-advertisement/PublishAdvertisementUseCase";
import { SqliteAdvertisementRepository } from "../advertisements/advertisement/infrastructure/persistence/SqliteAdvertisementRepository";
import { FrameworkRequest } from "./FrameworkRequest";
import { FrameworkResponse } from "./FrameworkResponse";
import { SqliteConnectionFactory } from "./database/SqliteConnectionFactory";
import { UpdateAdvertisementUseCase } from "../advertisements/advertisement/application/command/update-advertisement/UpdateAdvertisementUseCase";
import { UpdateAdvertisementController } from "../advertisements/advertisement/ui/Http/UpdateAdvertisementController";
import {RenewAdvertisementController} from "../advertisements/advertisement/ui/Http/RenewAdvertisementController";
import {RenewAdvertisementUseCase} from "../advertisements/advertisement/application/command/renew-advertisement/RenewAdvertisementUseCase";
import {FrameworkSecurityService} from "./security-user/FrameworkSecurityService";
import {SqliteSecurityUserRepository} from "./security-user/SqliteSecurityUserRepository";
import {SqliteUserRepository} from "../advertisements/user/infrastructure/persistence/SqliteUserRepository";

export class FrameworkServer {

  private constructor(
    private publishAdvertisementController: PublishAdvertisementController,
    private updatedAdvertisementController: UpdateAdvertisementController,
    private renewAdvertisementController: RenewAdvertisementController,
  ) { };

  static async start(): Promise<FrameworkServer> {
    const connection = await SqliteConnectionFactory.createClient();
    const advertisementRepository = new SqliteAdvertisementRepository(connection);
    const userRepository = new SqliteUserRepository(connection);
    const publishAdvertisementUseCase = new PublishAdvertisementUseCase(advertisementRepository, userRepository);
    const updateAdvertisementUseCase = new UpdateAdvertisementUseCase(advertisementRepository, userRepository);
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

    return new FrameworkServer(
        publishAdvertisementController,
        updateAdvertisementController,
        renewAdvertisementController
    );
  }

  async route(request: FrameworkRequest): Promise<FrameworkResponse> {

    const route = `${request.method}:/${request.path}`

    switch (route) {
      case "POST:/advertisement":
        return await this.publishAdvertisementController.execute(request)
      case "PUT:/advertisement":
        return await this.updatedAdvertisementController.execute(request)
      case "PATCH:/advertisement":
        return await this.renewAdvertisementController.execute(request)
      default:
        return Promise.resolve(new FrameworkResponse(404, { message: "Not Found" }))
    }
  }
}
