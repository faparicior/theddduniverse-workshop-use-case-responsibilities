package common.domain

import common.exceptions.BoundedContextException

open class DomainException(message: String) : BoundedContextException(message)  {
}