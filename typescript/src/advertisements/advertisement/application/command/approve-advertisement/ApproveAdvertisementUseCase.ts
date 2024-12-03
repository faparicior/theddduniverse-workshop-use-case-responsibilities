import { AdvertisementRepository } from "../../../domain/AdvertisementRepository"
import {AdvertisementId} from "../../../domain/value-object/AdvertisementId";
import {AdvertisementNotFoundException} from "../../../domain/exceptions/AdvertisementNotFoundException";
import {UserRepository} from "../../../../user/domain/UserRepository";
import {MemberDoesNotExistsException} from "../../../../user/domain/exceptions/MemberDoesNotExistsException";
import {UserId} from "../../../../shared/domain/value-object/UserId";
import {ApproveAdvertisementCommand} from "./ApproveAdvertisementCommand";
import {UserNotFoundException} from "../../../../user/domain/exceptions/UserNotFoundException";
import {
  AdminWithIncorrectCivicCenterException
} from "../../../../user/domain/exceptions/AdminWithIncorrectCivicCenterException";
import {AdvertisementSecurityService} from "../../../domain/services/AdvertisementSecurityService";

export class ApproveAdvertisementUseCase {

  constructor(
    private advertisementRepository: AdvertisementRepository,
    private userRepository: UserRepository,
    private securityService: AdvertisementSecurityService,
  ) {

  }

  async execute(command: ApproveAdvertisementCommand): Promise<void> {
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

    advertisement.approve()

    await this.advertisementRepository.save(advertisement)
  }
}
