import {UserRepository} from "../../../user/domain/UserRepository";
import {UserNotFoundException} from "../../../user/domain/exceptions/UserNotFoundException";
import {
    AdminWithIncorrectCivicCenterException
} from "../../../user/domain/exceptions/AdminWithIncorrectCivicCenterException";
import {UserId} from "../../../shared/domain/value-object/UserId";
import {UnauthorizedUserException} from "../exceptions/UnauthorizedUserException";
import {Advertisement} from "../Advertisement";
import {MemberUser} from "../../../user/domain/MemberUser";


export class AdvertisementSecurityService {
    constructor(private userRepository: UserRepository) {}

    public async verifyAdminUserCanManageAdvertisement(securityUserId: UserId, advertisement: Advertisement): Promise<void> {
        const adminUser = await this.userRepository.findAdminById(securityUserId);
        if (!adminUser) {
            throw UserNotFoundException.asAdmin();
        }

        if (!adminUser.civicCenterId().equals(advertisement.civicCenterId())) {
            throw AdminWithIncorrectCivicCenterException.differentCivicCenterFromMember();
        }
    }

    public async verifyMemberUserCanManageAdvertisement(securityUserId: UserId, advertisement: Advertisement): Promise<void> {
        const memberUser = await this.userRepository.findMemberById(securityUserId);
        if (!memberUser) {
            throw UserNotFoundException.asMember();
        }

        if (!advertisement.memberId().equals(securityUserId)) {
            throw UnauthorizedUserException.build();
        }
    }

    public async verifyAdminUserCanManageMemberUser(securityUserId: UserId, member: MemberUser): Promise<void> {
        const adminUser = await this.userRepository.findAdminById(securityUserId);
        if (!adminUser) {
            throw UserNotFoundException.asAdmin();
        }

        if (!adminUser.civicCenterId().equals(member.civicCenterId())) {
            throw AdminWithIncorrectCivicCenterException.differentCivicCenterFromMember();
        }
    }
}
