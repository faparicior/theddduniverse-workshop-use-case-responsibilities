import { AdvertisementRepository } from "../../../domain/AdvertisementRepository"
import {AdvertisementId} from "../../../domain/value-object/AdvertisementId";
import {AdvertisementNotFoundException} from "../../../domain/exceptions/AdvertisementNotFoundException";
import {UserId} from "../../../../shared/domain/value-object/UserId";
import {DisableAdvertisementCommand} from "./DisableAdvertisementCommand";
import {SecurityService} from "../../../domain/services/SecurityService";

export class DisableAdvertisementUseCase {

  constructor(
    private advertisementRepository: AdvertisementRepository,
    private securityService: SecurityService
  ) {

  }

  async execute(command: DisableAdvertisementCommand): Promise<void> {

    const advertisementId = new AdvertisementId(command.advertisementId)
    const advertisement = await this.advertisementRepository.findById(advertisementId)

    if (!advertisement) {
      throw AdvertisementNotFoundException.withId(advertisementId.value())
    }

    await this.securityService.verifyAdminUserCanManageAdvertisement(new UserId(command.securityUserId), advertisement)

    advertisement.disable()

    await this.advertisementRepository.save(advertisement)
  }
}
