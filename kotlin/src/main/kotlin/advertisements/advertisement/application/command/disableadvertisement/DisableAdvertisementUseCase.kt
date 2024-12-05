package advertisements.advertisement.application.command.disableadvertisement

import advertisements.advertisement.domain.exceptions.AdvertisementNotFoundException
import advertisements.advertisement.domain.AdvertisementRepository
import advertisements.advertisement.domain.services.AdvertisementSecurityService
import advertisements.advertisement.domain.value_object.AdvertisementId
import advertisements.shared.value_object.UserId

class DisableAdvertisementUseCase(
    private val advertisementRepository: AdvertisementRepository,
    private val advertisementSecurityService: AdvertisementSecurityService
) {
    fun execute(disableAdvertisementCommand: DisableAdvertisementCommand) {
        val advertisementId = AdvertisementId(disableAdvertisementCommand.advertisementId)
        val advertisement = advertisementRepository.findById(advertisementId)

        if (null === advertisement) {
            throw AdvertisementNotFoundException.withId(advertisementId.value())
        }

        advertisementSecurityService.verifyAdminUserCanManageAdvertisement(
            UserId(disableAdvertisementCommand.securityUserId),
            advertisement
        )

        advertisement.disable()

        advertisementRepository.save(advertisement)
    }
}
