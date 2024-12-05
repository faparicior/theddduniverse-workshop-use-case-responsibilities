package advertisements.advertisement.application.command.renewadvertisement

import advertisements.advertisement.domain.exceptions.AdvertisementNotFoundException
import advertisements.advertisement.application.exceptions.PasswordDoesNotMatchException
import advertisements.advertisement.domain.AdvertisementRepository
import advertisements.advertisement.domain.value_object.AdvertisementId
import advertisements.shared.value_object.Password
import framework.database.TransactionManager

class RenewAdvertisementUseCase(
    private val advertisementRepository: AdvertisementRepository,
    private val transactionManager: TransactionManager,
) {
    fun execute(renewAdvertisementCommand: RenewAdvertisementCommand) {
        transactionManager.beginTransaction()

        try {
            val advertisementId = AdvertisementId(renewAdvertisementCommand.id)
            val advertisement = advertisementRepository.findById(advertisementId)

            if (null === advertisement) {
                throw AdvertisementNotFoundException.withId(advertisementId.value())
            }

            if (!advertisement.password.isValidatedWith(renewAdvertisementCommand.password))
                throw PasswordDoesNotMatchException.build()

            advertisement.renew(Password.fromPlainPassword(renewAdvertisementCommand.password))

            advertisementRepository.save(advertisement)
            transactionManager.commit()
        } catch (e: Exception) {
            transactionManager.rollback()
            throw e
        }
    }
}
