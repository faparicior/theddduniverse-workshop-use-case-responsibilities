package advertisements.advertisement.application.command.renewAdvertisement

import advertisements.advertisement.domain.exceptions.AdvertisementNotFoundException
import advertisements.advertisement.application.exceptions.PasswordDoesNotMatchException
import advertisements.advertisement.domain.value_object.AdvertisementId
import advertisements.shared.value_object.Password

class RenewAdvertisementUseCase(private val advertisementRepository: advertisements.advertisement.domain.AdvertisementRepository) {
    fun execute(renewAdvertisementCommand: RenewAdvertisementCommand) {
        val advertisementId = AdvertisementId(renewAdvertisementCommand.id)
        val advertisement = advertisementRepository.findById(advertisementId)

        if (null === advertisement) {
            throw AdvertisementNotFoundException.withId(advertisementId.value())
        }

        if (!advertisement.password.isValidatedWith(renewAdvertisementCommand.password))
            throw PasswordDoesNotMatchException.build()

        advertisement.renew(Password.fromPlainPassword(renewAdvertisementCommand.password))

        advertisementRepository.save(advertisement)
    }
}