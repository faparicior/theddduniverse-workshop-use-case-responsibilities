import {ApplicationException} from "../../../../common/application/ApplicationException";

export class InvalidPasswordException extends ApplicationException {

  private static _message: string = 'Invalid password';

  private constructor(message?: string) {
    super(message);
    this.name = 'InvalidPasswordException';
    Object.setPrototypeOf(this, new.target.prototype);
  }

  static build(message: string = InvalidPasswordException._message): InvalidPasswordException {
    return new InvalidPasswordException(message);
  }
}
