package advertisements.advertisement.application.command.disableadvertisement

import advertisements.advertisement.domain.exceptions.AdvertisementNotFoundException
import advertisements.advertisement.domain.AdvertisementRepository
import advertisements.advertisement.domain.value_object.AdvertisementId
import advertisements.shared.value_object.UserId
import advertisements.user.domain.UserRepository
import advertisements.user.domain.exceptions.UserNotFoundException

class DisableAdvertisementUseCase(
    private val advertisementRepository: AdvertisementRepository,
    private val userRepository: UserRepository,
) {
    fun execute(disableAdvertisementCommand: DisableAdvertisementCommand) {
        // TODO: Find the bug in the following code
        val adminUser = userRepository.findAdminById(UserId(disableAdvertisementCommand.securityUserId))

        if(adminUser == null) {
            throw UserNotFoundException.asAdmin()
        }

        val advertisementId = AdvertisementId(disableAdvertisementCommand.advertisementId)
        val advertisement = advertisementRepository.findById(advertisementId)

        if (null === advertisement) {
            throw AdvertisementNotFoundException.withId(advertisementId.value())
        }

        advertisement.disable()

        advertisementRepository.save(advertisement)
    }
}
