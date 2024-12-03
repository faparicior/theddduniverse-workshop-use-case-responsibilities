import {BoundedContextException} from "../exceptions/BoundedContextException";

export class DomainException extends BoundedContextException {
  constructor(message?: string) {
    super(message);
    this.name = 'DomainException';
    Object.setPrototypeOf(this, new.target.prototype);
  }
}
