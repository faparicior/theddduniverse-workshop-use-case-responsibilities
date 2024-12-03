import {UserRepository} from "../../../domain/UserRepository";
import {UserId} from "../../../../shared/domain/value-object/UserId";
import {UserNotFoundException} from "../../../domain/exceptions/UserNotFoundException";
import {CivicCenterId} from "../../../../shared/domain/value-object/CivicCenterId";
import {SignUpMemberCommand} from "./SignUpMemberCommand";
import {
    AdminWithIncorrectCivicCenterException
} from "../../../domain/exceptions/AdminWithIncorrectCivicCenterException";
import {MemberAlreadyExistsException} from "../../../domain/exceptions/MemberAlreadyExistsException";
import {MemberUser} from "../../../domain/MemberUser";
import {Email} from "../../../../shared/domain/value-object/Email";
import {Password} from "../../../../shared/domain/value-object/Password";
import {Role} from "../../../domain/value-object/Role";
import {MemberNumber} from "../../../domain/value-object/MemberNumber";
import {TransactionManager} from "../../../../../framework/database/TransactionManager";


export class SignUpMemberUseCase {

    constructor(
        private userRepository: UserRepository,
        private transactionManager: TransactionManager,
    ) {

    }

    async execute(command: SignUpMemberCommand): Promise<void> {
        await this.transactionManager.beginTransaction()

        try {
            const adminUser = await this.userRepository.findAdminById(new UserId(command.securityUserId));
            if (!adminUser) {
                throw UserNotFoundException.asAdmin();
            }

            if (!adminUser.civicCenterId().equals(new CivicCenterId(command.civicCenterId))) {
                throw AdminWithIncorrectCivicCenterException.differentCivicCenterFromMember();
            }

            if (await this.userRepository.findMemberById(new UserId(command.memberId))) {
                throw MemberAlreadyExistsException.build();
            }

            const member = MemberUser.signUp(
                new UserId(command.memberId),
                new Email(command.email),
                await Password.fromPlainPassword(command.password),
                Role.MEMBER,
                new MemberNumber(command.memberNumber),
                new CivicCenterId(command.civicCenterId)
            );

            await this.userRepository.saveMember(member);
            await this.transactionManager.commit();
        } catch (error) {
            await this.transactionManager.rollback();
            throw error
        }
    }
}
