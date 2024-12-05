package advertisements.advertisement.domain.exceptions

import common.application.ApplicationException

class AdvertisementAlreadyExistsException private constructor(message: String) : ApplicationException(message) {

    companion object {
        fun withId(id: String): AdvertisementAlreadyExistsException {
            return AdvertisementAlreadyExistsException("Advertisement with id $id already exists")
        }
    }
}
