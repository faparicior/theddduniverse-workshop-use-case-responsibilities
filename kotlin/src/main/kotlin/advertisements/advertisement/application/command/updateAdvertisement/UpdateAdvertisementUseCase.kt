package advertisements.advertisement.application.command.updateAdvertisement

import advertisements.advertisement.domain.exceptions.AdvertisementNotFoundException
import advertisements.advertisement.application.exceptions.PasswordDoesNotMatchException
import advertisements.advertisement.domain.value_object.AdvertisementId
import advertisements.advertisement.domain.value_object.Description
import advertisements.shared.value_object.Email
import advertisements.shared.value_object.Password

class UpdateAdvertisementUseCase(private val advertisementRepository: advertisements.advertisement.domain.AdvertisementRepository) {
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
            Email(updateAdvertisementCommand.email),
            Password.fromPlainPassword(updateAdvertisementCommand.password)
        )

        advertisementRepository.save(advertisement)
    }
}
