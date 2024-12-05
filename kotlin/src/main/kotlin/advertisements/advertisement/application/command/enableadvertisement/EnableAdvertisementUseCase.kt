package advertisements.advertisement.application.command.enableadvertisement

import advertisements.advertisement.domain.exceptions.AdvertisementNotFoundException
import advertisements.advertisement.domain.AdvertisementRepository
import advertisements.advertisement.domain.services.AdvertisementSecurityService
import advertisements.advertisement.domain.value_object.AdvertisementId
import advertisements.shared.value_object.UserId
import advertisements.user.domain.UserRepository
import advertisements.user.domain.exceptions.UserNotFoundException
import framework.database.TransactionManager

class EnableAdvertisementUseCase(
    private val advertisementRepository: AdvertisementRepository,
    private val userRepository: UserRepository,
    private val advertisementSecurityService: AdvertisementSecurityService,
    private val transactionManager: TransactionManager,
) {
    fun execute(enableAdvertisementCommand: EnableAdvertisementCommand) {
        transactionManager.beginTransaction()

        try {
                val advertisementId = AdvertisementId(enableAdvertisementCommand.advertisementId)

            val advertisement = advertisementRepository.findById(advertisementId)

            if (null === advertisement) {
                throw AdvertisementNotFoundException.withId(advertisementId.value())
            }

            advertisementSecurityService.verifyAdminUserCanManageAdvertisement(
                UserId(enableAdvertisementCommand.securityUserId),
                advertisement
            )

            val memberUser = userRepository.findMemberById(advertisement.memberId)
            if (memberUser == null) {
                throw UserNotFoundException.asMember()
            }

            val activeAdvertisements = advertisementRepository.activeAdvertisementsByMember(memberUser)

            if (activeAdvertisements.value() >= 3) {
                throw Exception("Member has reached the maximum number of active advertisements")
            }

            advertisement.enable()

            advertisementRepository.save(advertisement)
            transactionManager.commit()
        } catch (e: Exception) {
            transactionManager.rollback()
            throw e
        }
    }
}
