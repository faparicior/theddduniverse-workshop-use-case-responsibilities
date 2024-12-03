import { AdvertisementRepository } from "../../../domain/AdvertisementRepository"
import {AdvertisementId} from "../../../domain/value-object/AdvertisementId";
import {AdvertisementNotFoundException} from "../../../domain/exceptions/AdvertisementNotFoundException";
import {DeleteAdvertisementCommand} from "./DeleteAdvertisementCommand";
import {UserId} from "../../../../shared/domain/value-object/UserId";
import {AdvertisementSecurityService} from "../../../domain/services/AdvertisementSecurityService";

export class DeleteAdvertisementUseCase {

  constructor(
    private advertisementRepository: AdvertisementRepository,
    private securityService: AdvertisementSecurityService,
  ) {

  }

  async execute(command: DeleteAdvertisementCommand): Promise<void> {

    const advertisementId = new AdvertisementId(command.advertisementId)
    const advertisement = await this.advertisementRepository.findById(advertisementId)

    if (!advertisement) {
      throw AdvertisementNotFoundException.withId(advertisementId.value())
    }

    await this.securityService.verifyMemberUserCanManageAdvertisement(new UserId(command.securityUserId), advertisement)

    await this.advertisementRepository.delete(advertisement)
  }
}
