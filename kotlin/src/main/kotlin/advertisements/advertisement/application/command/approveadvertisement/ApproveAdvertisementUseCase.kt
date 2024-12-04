package advertisements.advertisement.application.command.approveadvertisement

import advertisements.advertisement.domain.exceptions.AdvertisementNotFoundException
import advertisements.advertisement.domain.AdvertisementRepository
import advertisements.advertisement.domain.value_object.AdvertisementId
import advertisements.shared.value_object.UserId
import advertisements.user.domain.UserRepository
import advertisements.user.domain.exceptions.AdminWithIncorrectCivicCenterException
import advertisements.user.domain.exceptions.UserNotFoundException

class ApproveAdvertisementUseCase(
    private val advertisementRepository: AdvertisementRepository,
    private val userRepository: UserRepository,
) {
    fun execute(approveAdvertisementCommand: ApproveAdvertisementCommand) {
        // TODO: Different security behaviour compared from other use case
        val adminUser = userRepository.findAdminById(UserId(approveAdvertisementCommand.securityUserId))

        if(adminUser == null) {
            throw UserNotFoundException.asAdmin()
        }

        val advertisementId = AdvertisementId(approveAdvertisementCommand.advertisementId)
        val advertisement = advertisementRepository.findById(advertisementId)

        if (null === advertisement) {
            throw AdvertisementNotFoundException.withId(advertisementId.value())
        }

        if (!adminUser.civicCenterId().equals(advertisement.civicCenterId)) {
            throw AdminWithIncorrectCivicCenterException.differentCivicCenterFromMember()
        }

        advertisement.approve()

        advertisementRepository.save(advertisement)
    }
}
