package advertisements.user.application.command.disablemember

import advertisements.shared.value_object.UserId
import advertisements.user.domain.UserRepository
import advertisements.user.domain.exceptions.AdminWithIncorrectCivicCenterException
import advertisements.user.domain.exceptions.MemberDoesNotExistsException
import advertisements.user.domain.exceptions.UserNotFoundException

class DisableMemberUseCase(private val userRepository: UserRepository) {

    fun execute(command: DisableMemberCommand) {
        val adminUser = userRepository.findAdminById(UserId(command.securityUserId))
            ?: throw UserNotFoundException.asAdmin()

        val member = userRepository.findMemberById(UserId(command.memberId))
            ?: throw MemberDoesNotExistsException.build()

        if (!adminUser.civicCenterId().equals(member.civicCenterId())) {
            throw AdminWithIncorrectCivicCenterException.differentCivicCenterFromMember()
        }

        member.disable()

        userRepository.saveMember(member)
    }
}
