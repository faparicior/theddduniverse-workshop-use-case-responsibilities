import { SecurityUserRepository } from './SecurityUserRepository';
import { FrameworkRequest } from '../FrameworkRequest';
import { SecurityUser } from './SecurityUser';

export class FrameworkSecurityService {
    constructor(private securityUserRepository: SecurityUserRepository) {}

    public async getSecurityUserFromRequest(request: FrameworkRequest): Promise<SecurityUser | null> {
        return await this.securityUserRepository.findUserById(request.headers['userSession']);
    }
}
