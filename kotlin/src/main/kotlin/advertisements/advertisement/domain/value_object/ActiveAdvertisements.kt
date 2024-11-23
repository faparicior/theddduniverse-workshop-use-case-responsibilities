package advertisements.advertisement.domain.value_object

class ActiveAdvertisements private constructor(private val activeAdvertisements: Int) {

    companion object {
        fun fromInt(activeAdvertisements: Int): ActiveAdvertisements {
            return ActiveAdvertisements(activeAdvertisements)
        }
    }

    fun value(): Int {
        return activeAdvertisements
    }
}