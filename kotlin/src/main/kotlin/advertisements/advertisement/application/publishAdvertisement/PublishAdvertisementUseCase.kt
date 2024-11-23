package advertisements.advertisement.application.publishAdvertisement

import advertisements.advertisement.domain.Advertisement
import advertisements.advertisement.domain.value_object.AdvertisementDate
import advertisements.advertisement.domain.value_object.AdvertisementId
import advertisements.advertisement.domain.value_object.Description
import advertisements.shared.value_object.CivicCenterId
import advertisements.shared.value_object.Email
import advertisements.shared.value_object.Password
import advertisements.user.domain.value_object.MemberNumber
import java.time.LocalDateTime

class PublishAdvertisementUseCase(private val advertisementRepository: advertisements.advertisement.domain.AdvertisementRepository) {
    fun execute(publishAdvertisementCommand: advertisements.advertisement.application.publishAdvertisement.PublishAdvertisementCommand) {
        val advertisementId = AdvertisementId(publishAdvertisementCommand.id)

        if (null !== advertisementRepository.findById(advertisementId)) {
            throw advertisements.advertisement.domain.exceptions.AdvertisementAlreadyExistsException.withId(advertisementId.value())
        }

        val advertisement = Advertisement(
            advertisementId,
            Description(publishAdvertisementCommand.description),
            Email(publishAdvertisementCommand.email),
            Password.fromPlainPassword(publishAdvertisementCommand.password),
            AdvertisementDate(LocalDateTime.now()),
            CivicCenterId.create(publishAdvertisementCommand.civicCenterId),
            MemberNumber(publishAdvertisementCommand.memberId)
        )

        advertisementRepository.save(advertisement)
    }
}
