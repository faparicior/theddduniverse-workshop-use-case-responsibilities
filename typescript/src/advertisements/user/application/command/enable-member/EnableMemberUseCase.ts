import {UserRepository} from "../../../domain/UserRepository";
import {UserId} from "../../../../shared/domain/value-object/UserId";
import {UserNotFoundException} from "../../../domain/exceptions/UserNotFoundException";
import {EnableMemberCommand} from "./EnableMemberCommand";
import {
    AdminWithIncorrectCivicCenterException
} from "../../../domain/exceptions/AdminWithIncorrectCivicCenterException";
import {MemberAlreadyExistsException} from "../../../domain/exceptions/MemberAlreadyExistsException";
import {AdvertisementSecurityService} from "../../../../advertisement/domain/services/AdvertisementSecurityService";


export class EnableMemberUseCase {

    constructor(
        private userRepository: UserRepository,
        private securityService: AdvertisementSecurityService,
    ) {

    }

    async execute(command: EnableMemberCommand): Promise<void> {
        const member = await this.userRepository.findMemberById(new UserId(command.memberId))
        if (!member) {
            throw MemberAlreadyExistsException.build();
        }

        await this.securityService.verifyAdminUserCanManageMemberUser(new UserId(command.securityUserId), member)

        member.enable();

        await this.userRepository.saveMember(member);
    }
}
