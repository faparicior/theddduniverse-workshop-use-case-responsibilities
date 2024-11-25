import { AdvertisementRepository } from "../../domain/AdvertisementRepository";
import { Advertisement } from "../../domain/Advertisement";
import { PublishAdvertisementCommand } from "./PublishAdvertisementCommand";
import {Password} from "../../../shared/value-object/Password";
import {AdvertisementId} from "../../domain/value-object/AdvertisementId";
import {Description} from "../../domain/value-object/Description";
import {AdvertisementDate} from "../../domain/value-object/AdvertisementDate";
import {AdvertisementAlreadyExistsException} from "../../domain/exceptions/AdvertisementAlreadyExistsException";

export class PublishAdvertisementUseCase {

  constructor(
    private advertisementRepository: AdvertisementRepository
  ) {

  }

  async execute(command: PublishAdvertisementCommand): Promise<void> {
    const advertisementId = new AdvertisementId(command.id)

    if(await this.advertisementRepository.findById(advertisementId)) {
      throw AdvertisementAlreadyExistsException.withId(advertisementId.value())
    }

    const advertisement = new Advertisement(
      advertisementId,
      new Description(command.description),
      await Password.fromPlainPassword(command.password),
      new AdvertisementDate(new Date())
    )

    await this.advertisementRepository.save(advertisement)
  }
}
