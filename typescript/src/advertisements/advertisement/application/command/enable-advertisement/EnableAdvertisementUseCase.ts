import { AdvertisementRepository } from "../../../domain/AdvertisementRepository"
import {AdvertisementId} from "../../../domain/value-object/AdvertisementId";
import {AdvertisementNotFoundException} from "../../../domain/exceptions/AdvertisementNotFoundException";
import {UserRepository} from "../../../../user/domain/UserRepository";
import {MemberDoesNotExistsException} from "../../../../user/domain/exceptions/MemberDoesNotExistsException";
import {UserId} from "../../../../shared/domain/value-object/UserId";
import {UserNotFoundException} from "../../../../user/domain/exceptions/UserNotFoundException";
import {
  AdminWithIncorrectCivicCenterException
} from "../../../../user/domain/exceptions/AdminWithIncorrectCivicCenterException";
import {EnableAdvertisementCommand} from "./EnableAdvertisementCommand";
import {SecurityService} from "../../../domain/services/SecurityService";

export class EnableAdvertisementUseCase {

  constructor(
    private advertisementRepository: AdvertisementRepository,
    private userRepository: UserRepository,
    private securityService: SecurityService
  ) {

  }

  async execute(command: EnableAdvertisementCommand): Promise<void> {
    const advertisementId = new AdvertisementId(command.advertisementId)
    const advertisement = await this.advertisementRepository.findById(advertisementId)

    if (!advertisement) {
      throw AdvertisementNotFoundException.withId(advertisementId.value())
    }

    await this.securityService.verifyAdminUserCanManageAdvertisement(new UserId(command.securityUserId), advertisement)

    const member = await this.userRepository.findMemberById(advertisement.memberId())
    if (!member) {
      throw MemberDoesNotExistsException.build()
    }

    advertisement.enable()

    await this.advertisementRepository.save(advertisement)
  }
}
