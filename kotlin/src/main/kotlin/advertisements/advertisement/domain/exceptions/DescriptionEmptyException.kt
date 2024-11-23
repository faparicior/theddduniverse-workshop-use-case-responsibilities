package advertisements.advertisement.domain.exceptions

class DescriptionEmptyException private constructor(message: String) : Exception(message) {

    companion object {
        fun build(): advertisements.advertisement.domain.exceptions.DescriptionEmptyException {
            return advertisements.advertisement.domain.exceptions.DescriptionEmptyException("Description empty")
        }
    }
}
