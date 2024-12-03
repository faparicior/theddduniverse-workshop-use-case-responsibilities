import { AdvertisementRepository } from "../../../domain/AdvertisementRepository"
import {AdvertisementId} from "../../../domain/value-object/AdvertisementId";
import {AdvertisementNotFoundException} from "../../../domain/exceptions/AdvertisementNotFoundException";
import {DeleteAdvertisementCommand} from "./DeleteAdvertisementCommand";
import {UserId} from "../../../../shared/domain/value-object/UserId";
import {SecurityService} from "../../../domain/services/SecurityService";
import {TransactionManager} from "../../../../../framework/database/TransactionManager";

export class DeleteAdvertisementUseCase {

  constructor(
      private advertisementRepository: AdvertisementRepository,
      private securityService: SecurityService,
      private transactionManager: TransactionManager,
  ) {

  }

  async execute(command: DeleteAdvertisementCommand): Promise<void> {
    this.transactionManager.beginTransaction()

    try {
      const advertisementId = new AdvertisementId(command.advertisementId)
      const advertisement = await this.advertisementRepository.findById(advertisementId)

      if (!advertisement) {
        throw AdvertisementNotFoundException.withId(advertisementId.value())
      }

      await this.securityService.verifyMemberUserCanManageAdvertisement(new UserId(command.securityUserId), advertisement)

      await this.advertisementRepository.save(advertisement)
      await this.transactionManager.commit();
    } catch (error) {
      await this.transactionManager.rollback();
      throw error
    }
  }
}
