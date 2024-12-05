package advertisements.advertisement.application.exceptions

import common.application.ApplicationException

class PasswordDoesNotMatchException private constructor(message: String) : ApplicationException(message) {

    companion object {
        fun build(): PasswordDoesNotMatchException {
            return PasswordDoesNotMatchException("Password does not match")
        }
    }
}
