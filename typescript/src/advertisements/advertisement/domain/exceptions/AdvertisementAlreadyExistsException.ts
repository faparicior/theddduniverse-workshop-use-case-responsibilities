import {DomainException} from "../../../../common/domain/DomainException";
import {sprintf} from "sprintf-js";

export class AdvertisementAlreadyExistsException extends DomainException {

  private static _message: string = 'Advertisement with Id %s already exists';

  private constructor(message?: string) {
    super(message);
    this.name = 'AdvertisementAlreadyExistsException';
    Object.setPrototypeOf(this, new.target.prototype);
  }

  static withId(id: string): AdvertisementAlreadyExistsException {
    return new AdvertisementAlreadyExistsException(sprintf(this._message, id));
  }
}
