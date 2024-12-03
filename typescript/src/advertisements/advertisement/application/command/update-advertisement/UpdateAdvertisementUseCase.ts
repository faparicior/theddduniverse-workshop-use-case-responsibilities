import { AdvertisementRepository } from "../../../domain/AdvertisementRepository"
import { UpdateAdvertisementCommand } from "./UpdateAdvertisementCommand"
import {Password} from "../../../../shared/domain/value-object/Password";
import {Description} from "../../../domain/value-object/Description";
import {AdvertisementId} from "../../../domain/value-object/AdvertisementId";
import {InvalidPasswordException} from "../../exceptions/InvalidPasswordException";
import {AdvertisementNotFoundException} from "../../../domain/exceptions/AdvertisementNotFoundException";
import {Email} from "../../../../shared/domain/value-object/Email";
import {AdvertisementAlreadyExistsException} from "../../../domain/exceptions/AdvertisementAlreadyExistsException";
import {UserRepository} from "../../../../user/domain/UserRepository";
import {UserId} from "../../../../shared/domain/value-object/UserId";
import {UserNotFoundException} from "../../../../user/domain/exceptions/UserNotFoundException";
import {SecurityService} from "../../../domain/services/SecurityService";

export class UpdateAdvertisementUseCase {

  constructor(
    private advertisementRepository: AdvertisementRepository,
    private securityService: SecurityService
  ) {

  }

  async execute(command: UpdateAdvertisementCommand): Promise<void> {
    const advertisementId = new AdvertisementId(command.id)
    const advertisement = await this.advertisementRepository.findById(advertisementId)

    if (!advertisement) {
      throw AdvertisementNotFoundException.withId(advertisementId.value())
    }

    await this.securityService.verifyMemberUserCanManageAdvertisement(new UserId(command.securityUserId), advertisement)

    if (!await advertisement.password().isValid(command.password)) {
      throw InvalidPasswordException.build()
    }

    advertisement.update(
        new Description(command.description),
        new Email(command.email),
        await Password.fromPlainPassword(command.password)
    )

    await this.advertisementRepository.save(advertisement)
  }
}
