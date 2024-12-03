import {UserRepository} from "../../../domain/UserRepository";
import {UserId} from "../../../../shared/domain/value-object/UserId";
import {DisableMemberCommand} from "./DisableMemberCommand";
import {MemberAlreadyExistsException} from "../../../domain/exceptions/MemberAlreadyExistsException";
import {SecurityService} from "../../../../advertisement/domain/services/SecurityService";


export class DisableMemberUseCase {

    constructor(
        private userRepository: UserRepository,
        private securityService: SecurityService,
    ) {

    }

    async execute(command: DisableMemberCommand): Promise<void> {
        const member = await this.userRepository.findMemberById(new UserId(command.memberId))
        if (!member) {
            throw MemberAlreadyExistsException.build();
        }

        await this.securityService.verifyAdminUserCanManageMemberUser(new UserId(command.securityUserId), member)

        member.disable();

        await this.userRepository.saveMember(member);
    }
}
