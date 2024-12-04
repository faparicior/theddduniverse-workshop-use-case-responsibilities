package advertisements.advertisement.application.command.deleteadvertisement

import advertisements.advertisement.domain.exceptions.AdvertisementNotFoundException
import advertisements.advertisement.domain.AdvertisementRepository
import advertisements.advertisement.domain.value_object.AdvertisementId
import advertisements.shared.value_object.UserId
import advertisements.user.domain.UserRepository
import advertisements.user.domain.exceptions.UserNotFoundException

class DeleteAdvertisementUseCase(
    private val advertisementRepository: AdvertisementRepository,
    private val userRepository: UserRepository,
) {
    fun execute(deleteAdvertisementCommand: DeleteAdvertisementCommand) {
        // TODO: Find the bug in the following code
        val memberUser = userRepository.findMemberById(UserId(deleteAdvertisementCommand.securityUserId))

        if(memberUser == null) {
            throw UserNotFoundException.asMember()
        }

        val advertisementId = AdvertisementId(deleteAdvertisementCommand.advertisementId)
        val advertisement = advertisementRepository.findById(advertisementId)

        if (null === advertisement) {
            throw AdvertisementNotFoundException.withId(advertisementId.value())
        }

        advertisement.disable()

        advertisementRepository.delete(advertisement)
    }
}
