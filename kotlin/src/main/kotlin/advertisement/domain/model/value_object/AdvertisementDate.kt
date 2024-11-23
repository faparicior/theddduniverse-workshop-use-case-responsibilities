package advertisement.domain.model.value_object

import java.time.LocalDateTime

class AdvertisementDate(private var value: LocalDateTime) {

    fun value(): LocalDateTime {
        return value
    }
}
