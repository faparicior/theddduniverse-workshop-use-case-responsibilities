package advertisements.advertisement.domain.exceptions

import common.application.ApplicationException

class AdvertisementAlreadyExistsException private constructor(message: String) : ApplicationException(message) {

    companion object {
        fun withId(id: String): advertisements.advertisement.domain.exceptions.AdvertisementAlreadyExistsException {
            return advertisements.advertisement.domain.exceptions.AdvertisementAlreadyExistsException("Advertisement with id $id already exists")
        }
    }
}
