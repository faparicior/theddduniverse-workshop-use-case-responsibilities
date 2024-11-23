package advertisements.advertisement.domain

import advertisements.advertisement.domain.value_object.AdvertisementId
import advertisements.advertisement.domain.value_object.ActiveAdvertisements
import advertisements.user.domain.MemberUser

interface AdvertisementRepository {
    fun save(advertisement: Advertisement)
    fun findById(id: AdvertisementId): Advertisement?
    fun activeAdvertisementsByMember(member: MemberUser): ActiveAdvertisements
    fun delete(advertisement: Advertisement)
}