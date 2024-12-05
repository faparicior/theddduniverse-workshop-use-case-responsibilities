package advertisements.advertisement.domain.value_object

import java.time.LocalDateTime

class AdvertisementDate(private var value: LocalDateTime) {

    fun value(): LocalDateTime {
        return value
    }
}
