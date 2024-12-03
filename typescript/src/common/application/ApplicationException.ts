import {BoundedContextException} from "../exceptions/BoundedContextException";

export class ApplicationException extends BoundedContextException {
  constructor(message?: string) {
    super(message);
    this.name = 'ApplicationException';
    Object.setPrototypeOf(this, new.target.prototype);
  }
}
