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
import {UserNotFoundException} from "../../../../user/domain/exceptions/UserNotFoundException";
import {UserRepository} from "../../../../user/domain/UserRepository";

export class PublishAdvertisementUseCase {

  constructor(
    private advertisementRepository: AdvertisementRepository,
    private userRepository: UserRepository
  ) {

  }

  async execute(command: PublishAdvertisementCommand): Promise<void> {
    //TODO: Different behaviour message compared with other use case
    const memberUser = await this.userRepository.findMemberById(new UserId(command.securityUserId));
    if (!memberUser) {
      throw UserNotFoundException.asMember();
    }

    const advertisementId = new AdvertisementId(command.id)

    if(await this.advertisementRepository.findById(advertisementId)) {
      throw AdvertisementAlreadyExistsException.withId(advertisementId.value())
    }

    const activeAdvertisements = await this.advertisementRepository.activeAdvertisementsByMember(memberUser)

    if (activeAdvertisements.value() >= 3) {
      throw Error('Member has 3 active advertisements')
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
