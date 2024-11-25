import {DomainException} from "../../../../common/domain/DomainException";
import {sprintf} from "sprintf-js";

export class AdvertisementNotFoundException extends DomainException {

  private static _message: string = 'Advertisement not found with Id: %s';
  protected _code: number = 404;

  private constructor(message?: string) {
    super(message);
    this.name = 'AdvertisementNotFoundException';
    Object.setPrototypeOf(this, new.target.prototype);
  }

  static withId(id: string): AdvertisementNotFoundException {
    return new AdvertisementNotFoundException(sprintf(this._message, id));
  }
}
