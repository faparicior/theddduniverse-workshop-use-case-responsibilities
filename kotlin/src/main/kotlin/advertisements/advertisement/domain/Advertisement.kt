package advertisements.advertisement.domain

import advertisements.advertisement.domain.value_object.*
import advertisements.shared.value_object.*
import java.time.LocalDateTime

class Advertisement(
    val id: AdvertisementId,
    var description: Description,
    var email: Email,
    var password: Password,
    var date: AdvertisementDate,
    val civicCenterId: CivicCenterId,
    val memberId: UserId
) {
    var status: AdvertisementStatus = AdvertisementStatus.ENABLED
        private set
    var approvalStatus: AdvertisementApprovalStatus = AdvertisementApprovalStatus.PENDING_FOR_APPROVAL
        private set

    fun renew(password: Password) {
        this.password = password
        updateDate()
    }

    fun update(description: Description, email: Email, password: Password) {
        this.description = description
        this.email = email
        this.password = password
        updateDate()
    }

    private fun updateDate() {
        this.date = AdvertisementDate(LocalDateTime.now())
    }

    fun disable() {
        status = AdvertisementStatus.DISABLED
    }

    fun enable() {
        status = AdvertisementStatus.ENABLED
    }

    fun approve() {
        approvalStatus = AdvertisementApprovalStatus.APPROVED
    }
}
