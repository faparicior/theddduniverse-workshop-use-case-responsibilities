package advertisements.advertisement.application.command.disableadvertisement

import advertisements.advertisement.domain.exceptions.AdvertisementNotFoundException
import advertisements.advertisement.domain.AdvertisementRepository
import advertisements.advertisement.domain.services.AdvertisementSecurityService
import advertisements.advertisement.domain.value_object.AdvertisementId
import advertisements.shared.value_object.UserId
import framework.database.TransactionManager

class DisableAdvertisementUseCase(
    private val advertisementRepository: AdvertisementRepository,
    private val advertisementSecurityService: AdvertisementSecurityService,
    private val transactionManager: TransactionManager,
) {
    fun execute(disableAdvertisementCommand: DisableAdvertisementCommand) {
        transactionManager.beginTransaction()

        try {
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
            transactionManager.commit()
        } catch (e: Exception) {
            transactionManager.rollback()
            throw e
        }
    }
}
