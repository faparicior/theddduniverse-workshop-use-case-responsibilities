package advertisements.advertisement.domain

import advertisements.advertisement.domain.value_object.AdvertisementId

interface AdvertisementRepository {
    fun save(advertisement: Advertisement)
    fun findById(id: AdvertisementId): Advertisement?
}