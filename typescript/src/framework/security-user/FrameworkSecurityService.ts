import { SecurityUserRepository } from './SecurityUserRepository';
import { FrameworkRequest } from '../FrameworkRequest';
import { SecurityUser } from './SecurityUser';

export class FrameworkSecurityService {
    constructor(private securityUserRepository: SecurityUserRepository) {}

    public getSecurityUserFromRequest(request: FrameworkRequest): SecurityUser | null {
        return this.securityUserRepository.findUserById(request.headers()['userSession']);
    }
}
