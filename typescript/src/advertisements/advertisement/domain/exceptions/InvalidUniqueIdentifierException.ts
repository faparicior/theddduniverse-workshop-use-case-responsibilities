import {DomainException} from "../../../../common/domain/DomainException";

export class InvalidUniqueIdentifierException extends DomainException {
  private static _message: string = "Invalid unique identifier format for ";

  private constructor(message?: string) {
    super(message);
    this.name = 'InvalidUniqueIdentifierException';
    Object.setPrototypeOf(this, new.target.prototype);
  }

  static withId(value: string): InvalidUniqueIdentifierException {
    return new InvalidUniqueIdentifierException(this._message + value);
  }
}
