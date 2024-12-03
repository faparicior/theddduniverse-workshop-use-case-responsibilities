import {UserRepository} from "../../../domain/UserRepository";
import {UserId} from "../../../../shared/domain/value-object/UserId";
import {DisableMemberCommand} from "./DisableMemberCommand";
import {MemberAlreadyExistsException} from "../../../domain/exceptions/MemberAlreadyExistsException";
import {SecurityService} from "../../../../advertisement/domain/services/SecurityService";
import {TransactionManager} from "../../../../../framework/database/TransactionManager";


export class DisableMemberUseCase {

    constructor(
        private userRepository: UserRepository,
        private securityService: SecurityService,
        private transactionManager: TransactionManager,
    ) {

    }

    async execute(command: DisableMemberCommand): Promise<void> {
        await this.transactionManager.beginTransaction()

        try {
            const member = await this.userRepository.findMemberById(new UserId(command.memberId))
            if (!member) {
                throw MemberAlreadyExistsException.build();
            }

            await this.securityService.verifyAdminUserCanManageMemberUser(new UserId(command.securityUserId), member)

            member.disable();

            await this.userRepository.saveMember(member);
            await this.transactionManager.commit();
        } catch (error) {
            await this.transactionManager.rollback();
            throw error
        }
    }
}
