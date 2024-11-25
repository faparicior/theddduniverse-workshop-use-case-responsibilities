import { AdvertisementRepository } from "../../../domain/AdvertisementRepository"
import { RenewAdvertisementCommand } from "./RenewAdvertisementCommand"
import {Password} from "../../../../shared/domain/value-object/Password";
import {AdvertisementId} from "../../../domain/value-object/AdvertisementId";
import {InvalidPasswordException} from "../../exceptions/InvalidPasswordException";
import {AdvertisementNotFoundException} from "../../../domain/exceptions/AdvertisementNotFoundException";

export class RenewAdvertisementUseCase {

  constructor(
    private advertisementRepository: AdvertisementRepository
  ) {

  }

  async execute(command: RenewAdvertisementCommand): Promise<void> {
    const advertisementId = new AdvertisementId(command.id)
    const advertisement = await this.advertisementRepository.findById(advertisementId)

    if (!advertisement) {
      throw AdvertisementNotFoundException.withId(advertisementId.value())
    }

    if (!await advertisement.password().isValid(command.password)) {
      throw InvalidPasswordException.build()
    }

    advertisement.renew(await Password.fromPlainPassword(command.password))

    await this.advertisementRepository.save(advertisement)
  }
}
