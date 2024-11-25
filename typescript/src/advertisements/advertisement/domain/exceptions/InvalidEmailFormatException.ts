import {DomainException} from "../../../../common/domain/DomainException";
import {sprintf} from "sprintf-js";

export class InvalidEmailFormatException extends DomainException {
  private static _message: string = "Invalid email format: %s";

  private constructor(message?: string) {
    super(message);
    this.name = 'InvalidEmailFormatException';
    Object.setPrototypeOf(this, new.target.prototype);
  }

  static withEmail(value: string): InvalidEmailFormatException {
    return new InvalidEmailFormatException(sprintf(this._message, value.length));
  }
}
