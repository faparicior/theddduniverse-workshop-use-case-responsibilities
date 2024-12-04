package advertisements.user.application.command.signupmember

import advertisements.shared.value_object.CivicCenterId
import advertisements.shared.value_object.Email
import advertisements.shared.value_object.Password
import advertisements.shared.value_object.UserId
import advertisements.user.domain.MemberUser
import advertisements.user.domain.UserRepository
import advertisements.user.domain.exceptions.AdminWithIncorrectCivicCenterException
import advertisements.user.domain.exceptions.MemberAlreadyExistsException
import advertisements.user.domain.exceptions.UserNotFoundException
import advertisements.user.domain.value_object.MemberNumber
import advertisements.user.domain.value_object.Role

class SignUpMemberUseCase(private val userRepository: UserRepository) {

    fun execute(command: SignUpMemberCommand) {
        val adminUser = userRepository.findAdminById(UserId(command.securityUserId))
            ?: throw UserNotFoundException.asAdmin()

        if (!adminUser.civicCenterId().equals(CivicCenterId.create(command.civicCenterId))) {
            throw AdminWithIncorrectCivicCenterException.differentCivicCenterFromMember()
        }

        if (userRepository.findMemberById(UserId(command.memberId)) != null) {
            throw MemberAlreadyExistsException.build()
        }

        val member = MemberUser.signUp(
            UserId(command.memberId),
            Email(command.email),
            Password.fromPlainPassword(command.password),
            Role.MEMBER,
            MemberNumber(command.memberNumber),
            CivicCenterId.create(command.civicCenterId)
        )

        userRepository.saveMember(member)
    }
}
