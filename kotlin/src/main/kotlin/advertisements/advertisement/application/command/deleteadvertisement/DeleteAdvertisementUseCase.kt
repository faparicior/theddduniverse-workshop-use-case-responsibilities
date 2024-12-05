package advertisements.advertisement.application.command.deleteadvertisement

import advertisements.advertisement.domain.exceptions.AdvertisementNotFoundException
import advertisements.advertisement.domain.AdvertisementRepository
import advertisements.advertisement.domain.services.AdvertisementSecurityService
import advertisements.advertisement.domain.value_object.AdvertisementId
import advertisements.shared.value_object.UserId

class DeleteAdvertisementUseCase(
    private val advertisementRepository: AdvertisementRepository,
    private val advertisementSecurityService: AdvertisementSecurityService
) {
    fun execute(deleteAdvertisementCommand: DeleteAdvertisementCommand) {
        val advertisementId = AdvertisementId(deleteAdvertisementCommand.advertisementId)
        val advertisement = advertisementRepository.findById(advertisementId)

        if (null === advertisement) {
            throw AdvertisementNotFoundException.withId(advertisementId.value())
        }

        advertisementSecurityService.verifyMemberUserCanManageAdvertisement(
            UserId(deleteAdvertisementCommand.securityUserId),
            advertisement
        )

        advertisement.disable()

        advertisementRepository.delete(advertisement)
    }
}
