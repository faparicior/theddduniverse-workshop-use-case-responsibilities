import {UserRepository} from "../../../domain/UserRepository";
import {UserId} from "../../../../shared/domain/value-object/UserId";
import {UserNotFoundException} from "../../../domain/exceptions/UserNotFoundException";
import {EnableMemberCommand} from "./EnableMemberCommand";
import {
    AdminWithIncorrectCivicCenterException
} from "../../../domain/exceptions/AdminWithIncorrectCivicCenterException";
import {MemberAlreadyExistsException} from "../../../domain/exceptions/MemberAlreadyExistsException";


export class EnableMemberUseCase {

    constructor(
        private userRepository: UserRepository,
    ) {

    }

    async execute(command: EnableMemberCommand): Promise<void> {
        const adminUser = await this.userRepository.findAdminById(new UserId(command.securityUserId));
        if (!adminUser) {
            throw UserNotFoundException.asAdmin();
        }

        const member = await this.userRepository.findMemberById(new UserId(command.memberId))
        if (!member) {
            throw MemberAlreadyExistsException.build();
        }

        if (!adminUser.civicCenterId().equals(member.civicCenterId())) {
            throw AdminWithIncorrectCivicCenterException.differentCivicCenterFromMember();
        }

        member.enable();

        await this.userRepository.saveMember(member);
    }
}
