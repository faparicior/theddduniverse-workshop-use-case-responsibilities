package advertisements.user.domain

import advertisements.shared.value_object.UserId

interface UserRepository {
    fun findAdminById(id: UserId): AdminUser?
    fun findMemberById(id: UserId): MemberUser?
    fun findAdminOrMemberById(id: UserId): Any? // AdminUser or MemberUser or null
    fun saveMember(member: MemberUser)
}
