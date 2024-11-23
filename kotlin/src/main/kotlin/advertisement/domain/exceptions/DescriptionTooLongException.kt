package advertisement.domain.exceptions

class DescriptionTooLongException private constructor(message: String) : Exception(message) {

    companion object {
        fun withLongitudeMessage(description: String): DescriptionTooLongException {
            val length = description.length
            return DescriptionTooLongException("DDescription has more than 200 characters: Has $length characters")
        }
    }
}
