import { PublishAdvertisementController } from "../advertisements/advertisement/ui/Http/PublishAdvertisementController";
import { PublishAdvertisementUseCase } from "../advertisements/advertisement/application/publish-advertisement/PublishAdvertisementUseCase";
import { SqliteAdvertisementRepository } from "../advertisements/advertisement/infrastructure/persistence/SqliteAdvertisementRepository";
import { FrameworkRequest } from "./FrameworkRequest";
import { FrameworkResponse } from "./FrameworkResponse";
import { SqliteConnectionFactory } from "./database/SqliteConnectionFactory";
import { UpdateAdvertisementUseCase } from "../advertisements/advertisement/application/update-advertisement/UpdateAdvertisementUseCase";
import { UpdateAdvertisementController } from "../advertisements/advertisement/ui/Http/UpdateAdvertisementController";
import {RenewAdvertisementController} from "../advertisements/advertisement/ui/Http/RenewAdvertisementController";
import {RenewAdvertisementUseCase} from "../advertisements/advertisement/application/renew-advertisement/RenewAdvertisementUseCase";

export class FrameworkServer {

  private constructor(
    private publishAdvertisementController: PublishAdvertisementController,
    private updatedAdvertisementController: UpdateAdvertisementController,
    private renewAdvertisementController: RenewAdvertisementController,
  ) { };

  static async start(): Promise<FrameworkServer> {
    const connection = await SqliteConnectionFactory.createClient();
    const advertisementRepository = new SqliteAdvertisementRepository(connection);
    const publishAdvertisementUseCase = new PublishAdvertisementUseCase(advertisementRepository);
    const updateAdvertisementUseCase = new UpdateAdvertisementUseCase(advertisementRepository);
    const publishAdvertisementController = new PublishAdvertisementController(publishAdvertisementUseCase)
    const updateAdvertisementController = new UpdateAdvertisementController(updateAdvertisementUseCase)
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
      case "PUT:/advertisements":
        return await this.updatedAdvertisementController.execute(request)
      case "PATCH:/advertisements":
        return await this.renewAdvertisementController.execute(request)
      default:
        return Promise.resolve(new FrameworkResponse(404, { message: "Not Found" }))
    }
  }
}
