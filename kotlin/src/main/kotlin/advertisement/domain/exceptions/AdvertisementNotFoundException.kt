package advertisement.domain.exceptions

import common.application.ElementNotFoundException

class AdvertisementNotFoundException private constructor(message: String) : ElementNotFoundException(message) {

    companion object {
        fun withId(id: String): AdvertisementNotFoundException {
            return AdvertisementNotFoundException("Advertisement not found with Id $id")
        }
    }
}
