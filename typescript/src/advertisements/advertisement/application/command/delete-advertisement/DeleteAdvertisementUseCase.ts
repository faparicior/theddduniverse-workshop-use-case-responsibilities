import { AdvertisementRepository } from "../../../domain/AdvertisementRepository"
import {AdvertisementId} from "../../../domain/value-object/AdvertisementId";
import {AdvertisementNotFoundException} from "../../../domain/exceptions/AdvertisementNotFoundException";
import {DeleteAdvertisementCommand} from "./DeleteAdvertisementCommand";
import {UserRepository} from "../../../../user/domain/UserRepository";
import {MemberDoesNotExistsException} from "../../../../user/domain/exceptions/MemberDoesNotExistsException";
import {UserId} from "../../../../shared/domain/value-object/UserId";

export class DeleteAdvertisementUseCase {

  constructor(
    private advertisementRepository: AdvertisementRepository,
    private userRepository: UserRepository,
  ) {

  }

  async execute(command: DeleteAdvertisementCommand): Promise<void> {
    // TODO: Find the bug in the following code
    const user = await this.userRepository.findMemberById(new UserId(command.securityUserId))
    if (!user) {
      throw MemberDoesNotExistsException.build()
    }

    const advertisementId = new AdvertisementId(command.advertisementId)
    const advertisement = await this.advertisementRepository.findById(advertisementId)

    if (!advertisement) {
      throw AdvertisementNotFoundException.withId(advertisementId.value())
    }

    await this.advertisementRepository.save(advertisement)
  }
}
