import {DomainException} from "../../../../common/domain/DomainException";

export class DescriptionEmptyException extends DomainException {
  private static _message: string = 'Description empty';

  private constructor(message?: string) {
    super(message);
    this.name = 'DescriptionEmptyException';
    Object.setPrototypeOf(this, new.target.prototype);
  }

  static build(): DescriptionEmptyException {
    return new DescriptionEmptyException(this._message);
  }
}
