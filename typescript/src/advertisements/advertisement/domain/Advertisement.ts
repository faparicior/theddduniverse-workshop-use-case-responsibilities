import {Password} from "./value-object/Password";
import {Description} from "./value-object/Description";
import {AdvertisementId} from "./value-object/AdvertisementId";
import {AdvertisementDate} from "./value-object/AdvertisementDate";

export class Advertisement {

  constructor(
    private readonly _id: AdvertisementId,
    private _description: Description,
    private _password: Password,
    private _date: AdvertisementDate
  ) {
  }

  public update(description: Description, password: Password): void {
    this._description = description;
    this._password = password;
    this.updateDate();
  }

  public renew(password: Password): void {
    this._password = password;
    this.updateDate();
  }

  public id(): AdvertisementId {
    return this._id
  }

  public description(): Description {
    return this._description
  }

  public password(): Password {
    return this._password
  }

  public date(): AdvertisementDate {
    return this._date
  }

  private updateDate(): void {
    this._date = new AdvertisementDate(new Date());
  }
}
