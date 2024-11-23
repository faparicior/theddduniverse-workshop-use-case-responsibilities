package advertisement.application.renewAdvertisement

import advertisement.domain.exceptions.AdvertisementNotFoundException
import advertisement.application.exceptions.PasswordDoesNotMatchException
import advertisement.domain.AdvertisementRepository
import advertisement.domain.model.value_object.AdvertisementId
import advertisement.domain.model.value_object.Password

class RenewAdvertisementUseCase(private val advertisementRepository: AdvertisementRepository) {
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
