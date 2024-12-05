package advertisements.advertisement.domain.services

import advertisements.advertisement.domain.Advertisement
import advertisements.shared.value_object.UserId
import advertisements.user.domain.UserRepository
import advertisements.user.domain.exceptions.AdminWithIncorrectCivicCenterException
import advertisements.user.domain.exceptions.UnauthorizedUserException
import advertisements.user.domain.exceptions.UserNotFoundException

class AdvertisementSecurityService(private val userRepository: UserRepository) {

    fun verifyAdminUserCanManageAdvertisement(securityUserId: UserId, advertisement: Advertisement) {
        val adminUser = userRepository.findAdminById(securityUserId)
            ?: throw UserNotFoundException.asAdmin()

        if (!adminUser.civicCenterId().equals(advertisement.civicCenterId)) {
            throw AdminWithIncorrectCivicCenterException.differentCivicCenterFromMember()
        }
    }

    fun verifyMemberUserCanManageAdvertisement(securityUserId: UserId, advertisement: Advertisement) {
        val memberUser = userRepository.findMemberById(securityUserId)
            ?: throw UserNotFoundException.asMember()

        if (!advertisement.memberId.equals(securityUserId)) {
            throw UnauthorizedUserException.build()
        }
    }
}
