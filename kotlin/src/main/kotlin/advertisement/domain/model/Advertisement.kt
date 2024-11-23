package advertisement.domain.model

import advertisement.domain.model.value_object.AdvertisementDate
import advertisement.domain.model.value_object.AdvertisementId
import advertisement.domain.model.value_object.Description
import advertisement.domain.model.value_object.Password
import java.time.LocalDateTime

data class Advertisement(val id: AdvertisementId, var description: Description, var password: Password, var date: AdvertisementDate)
{
    fun update(description: Description, password: Password)
    {
        this.description = description
        this.password = password
        updateDate()
    }

    fun renew(password: Password)
    {
        this.password = password
        updateDate()
    }

    private fun updateDate()
    {
        this.date = AdvertisementDate(LocalDateTime.now())
    }
}
