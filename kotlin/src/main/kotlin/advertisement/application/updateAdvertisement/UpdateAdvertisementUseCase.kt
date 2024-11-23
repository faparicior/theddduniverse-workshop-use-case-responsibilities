package advertisement.application.updateAdvertisement

import advertisement.domain.exceptions.AdvertisementNotFoundException
import advertisement.application.exceptions.PasswordDoesNotMatchException
import advertisement.domain.AdvertisementRepository
import advertisement.domain.model.value_object.AdvertisementId
import advertisement.domain.model.value_object.Description
import advertisement.domain.model.value_object.Password

class UpdateAdvertisementUseCase(private val advertisementRepository: AdvertisementRepository) {
    fun execute(updateAdvertisementCommand: UpdateAdvertisementCommand) {
        val advertisementId = AdvertisementId(updateAdvertisementCommand.id)
        val advertisement = advertisementRepository.findById(advertisementId)

        if (null === advertisement) {
            throw AdvertisementNotFoundException.withId(advertisementId.value())
        }

        if (!advertisement.password.isValidatedWith(updateAdvertisementCommand.password))
            throw PasswordDoesNotMatchException.build()

        advertisement.update(
            Description(updateAdvertisementCommand.description),
            Password.fromPlainPassword(updateAdvertisementCommand.password)
        )

        advertisementRepository.save(advertisement)
    }
}
