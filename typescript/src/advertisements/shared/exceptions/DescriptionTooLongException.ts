import {DomainException} from "../../../common/domain/DomainException";
import {sprintf} from "sprintf-js";

export class DescriptionTooLongException extends DomainException {
  private static _message: string = "Description has more than 200 characters: Has %d characters";

  private constructor(message?: string) {
    super(message);
    this.name = 'DescriptionTooLongException';
    Object.setPrototypeOf(this, new.target.prototype);
  }

  static withLongitudeMessage(value: string): DescriptionTooLongException {
    return new DescriptionTooLongException(sprintf(this._message, value.length));
  }
}
