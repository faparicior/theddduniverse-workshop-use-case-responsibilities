package framework.securityuser

interface SecurityUserRepository {
    fun findUserById(id: String): SecurityUser?
}
