package advertisements.advertisement.application.command.publishadvertisement

import advertisements.advertisement.domain.*
import advertisements.advertisement.domain.exceptions.AdvertisementAlreadyExistsException
import advertisements.advertisement.domain.value_object.*
import advertisements.shared.value_object.*
import advertisements.user.domain.exceptions.UserNotFoundException
import advertisements.user.domain.UserRepository
import framework.database.TransactionManager
import java.time.LocalDateTime

class PublishAdvertisementUseCase(
    private val advertisementRepository: AdvertisementRepository,
    private val userRepository: UserRepository,
    private val transactionManager: TransactionManager,
) {
    fun execute(command: PublishAdvertisementCommand) {
        transactionManager.beginTransaction()

        try {
            val memberUser = userRepository.findMemberById(UserId(command.securityUserId))
                ?: throw UserNotFoundException.asMember()

            if (advertisementRepository.findById(AdvertisementId(command.id)) != null) {
                throw AdvertisementAlreadyExistsException.withId(command.id)
            }

            val activeAdvertisements = advertisementRepository.activeAdvertisementsByMember(memberUser)

            if (activeAdvertisements.value() >= 3) {
                throw Exception("Member has 3 active advertisements")
            }

            val advertisement = Advertisement(
                AdvertisementId(command.id),
                Description(command.description),
                Email(command.email),
                Password.fromPlainPassword(command.password),
                AdvertisementDate(LocalDateTime.now()),
                CivicCenterId.create(command.civicCenterId),
                UserId(command.memberNumber)
            )

            advertisementRepository.save(advertisement)
            transactionManager.commit()
        } catch (e: Exception) {
            transactionManager.rollback()
            throw e
        }
    }
}