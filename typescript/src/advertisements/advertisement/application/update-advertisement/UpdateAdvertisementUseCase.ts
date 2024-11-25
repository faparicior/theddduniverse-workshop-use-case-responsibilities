import { AdvertisementRepository } from "../../domain/AdvertisementRepository"
import { UpdateAdvertisementCommand } from "./UpdateAdvertisementCommand"
import {Password} from "../../../shared/value-object/Password";
import {Description} from "../../domain/value-object/Description";
import {AdvertisementId} from "../../domain/value-object/AdvertisementId";
import {InvalidPasswordException} from "../exceptions/InvalidPasswordException";
import {AdvertisementNotFoundException} from "../../domain/exceptions/AdvertisementNotFoundException";

export class UpdateAdvertisementUseCase {

  constructor(
    private advertisementRepository: AdvertisementRepository
  ) {

  }

  async execute(command: UpdateAdvertisementCommand): Promise<void> {
    const advertisementId = new AdvertisementId(command.id)

    const advertisement = await this.advertisementRepository.findById(advertisementId)

    if (!advertisement) {
      throw AdvertisementNotFoundException.withId(advertisementId.value())
    }

    if (!await advertisement.password().isValid(command.password)) {
      throw InvalidPasswordException.build()
    }

    advertisement.update(
        new Description(command.description),
        await Password.fromPlainPassword(command.password)
    )

    await this.advertisementRepository.save(advertisement)
  }
}
