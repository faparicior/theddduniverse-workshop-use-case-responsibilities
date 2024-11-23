package advertisement.domain.exceptions

class DescriptionEmptyException private constructor(message: String) : Exception(message) {

    companion object {
        fun build(): DescriptionEmptyException {
            return DescriptionEmptyException("Description empty")
        }
    }
}
