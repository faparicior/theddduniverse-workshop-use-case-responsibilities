package common.application

import common.exceptions.BoundedContextException

open class ApplicationException(message: String) : BoundedContextException(message)  {
}