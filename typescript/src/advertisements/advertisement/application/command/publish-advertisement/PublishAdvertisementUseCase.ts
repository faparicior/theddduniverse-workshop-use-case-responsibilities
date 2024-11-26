import { AdvertisementRepository } from "../../../domain/AdvertisementRepository";
import { Advertisement } from "../../../domain/Advertisement";
import { PublishAdvertisementCommand } from "./PublishAdvertisementCommand";
import {Password} from "../../../../shared/domain/value-object/Password";
import {AdvertisementId} from "../../../domain/value-object/AdvertisementId";
import {Description} from "../../../domain/value-object/Description";
import {AdvertisementDate} from "../../../domain/value-object/AdvertisementDate";
import {AdvertisementAlreadyExistsException} from "../../../domain/exceptions/AdvertisementAlreadyExistsException";
import {Email} from "../../../../shared/domain/value-object/Email";
import {UserId} from "../../../../shared/domain/value-object/UserId";
import {CivicCenterId} from "../../../../shared/domain/value-object/CivicCenterId";

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
      new Email(command.email),
      await Password.fromPlainPassword(command.password),
      new AdvertisementDate(new Date()),
      new CivicCenterId(command.civicCenterId),
      new UserId(command.memberNumber)
    )

    await this.advertisementRepository.save(advertisement)
  }
}
