package framework.securityuser

import framework.FrameworkRequest

class FrameworkSecurityService(private val securityUserRepository: SecurityUserRepository) {

    fun getSecurityUserFromRequest(request: FrameworkRequest): SecurityUser? {
        return securityUserRepository.findUserById(request.headers["userSession"]!!)
    }
}
