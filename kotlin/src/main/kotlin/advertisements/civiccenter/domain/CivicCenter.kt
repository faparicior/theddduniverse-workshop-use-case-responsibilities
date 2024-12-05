package advertisements.civiccenter.domain

import advertisements.shared.value_object.CivicCenterId
import advertisements.civiccenter.domain.value_object.CivicCenterName

class CivicCenter(
    val id: CivicCenterId,
    var name: CivicCenterName
) {
    fun id(): CivicCenterId {
        return id
    }

    fun name(): CivicCenterName {
        return name
    }
}