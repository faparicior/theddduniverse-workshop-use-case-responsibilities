import { AdvertisementRepository } from "../../../domain/AdvertisementRepository"
import {AdvertisementId} from "../../../domain/value-object/AdvertisementId";
import {AdvertisementNotFoundException} from "../../../domain/exceptions/AdvertisementNotFoundException";
import {UserRepository} from "../../../../user/domain/UserRepository";
import {MemberDoesNotExistsException} from "../../../../user/domain/exceptions/MemberDoesNotExistsException";
import {UserId} from "../../../../shared/domain/value-object/UserId";
import {ApproveAdvertisementCommand} from "./ApproveAdvertisementCommand";
import {SecurityService} from "../../../domain/services/SecurityService";
import {TransactionManager} from "../../../../../framework/database/TransactionManager";

export class ApproveAdvertisementUseCase {

  constructor(
    private advertisementRepository: AdvertisementRepository,
    private userRepository: UserRepository,
    private securityService: SecurityService,
    private transactionManager: TransactionManager,
  ) {

  }

  async execute(command: ApproveAdvertisementCommand): Promise<void> {
    this.transactionManager.beginTransaction()

    try {
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
      await this.transactionManager.commit();
    } catch (error) {
      await this.transactionManager.rollback();
      throw error
    }
  }
}
