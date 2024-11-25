import {Password} from "../../shared/value-object/Password";
import {Description} from "./value-object/Description";
import {AdvertisementId} from "./value-object/AdvertisementId";
import {AdvertisementDate} from "./value-object/AdvertisementDate";
import {AdvertisementApprovalStatus} from "./value-object/AdvertisementApprovalStatus";
import {AdvertisementStatus} from "./value-object/AdvertisementStatusHelper";
import {Email} from "../../shared/value-object/Email";
import {UserId} from "../../shared/value-object/UserId";
import {CivicCenterId} from "../../shared/value-object/CivicCenterId";

export class Advertisement {
  private _status: AdvertisementStatus;
  private _approvalStatus: AdvertisementApprovalStatus;

  constructor(
      private readonly _id: AdvertisementId,
      private _description: Description,
      private _email: Email,
      private _password: Password,
      private _date: AdvertisementDate,
      private readonly _civicCenterId: CivicCenterId,
      private readonly _memberId: UserId
  ) {
    this._status = AdvertisementStatus.fromString('enabled');
    this._approvalStatus = AdvertisementApprovalStatus.fromString('pending_for_approval');
  }

  public renew(password: Password): void {
    this._password = password;
    this.updateDate();
  }

  public update(description: Description, email: Email, password: Password): void {
    this._description = description;
    this._email = email;
    this._password = password;
    this.updateDate();
  }

  public id(): AdvertisementId {
    return this._id;
  }

  public description(): Description {
    return this._description;
  }

  public email(): Email {
    return this._email;
  }

  public password(): Password {
    return this._password;
  }

  public date(): AdvertisementDate {
    return this._date;
  }

  private updateDate(): void {
    this._date = new AdvertisementDate(new Date());
  }

  public status(): AdvertisementStatus {
    return this._status;
  }

  public approvalStatus(): AdvertisementApprovalStatus {
    return this._approvalStatus;
  }

  public memberId(): UserId {
    return this._memberId;
  }

  public civicCenterId(): CivicCenterId {
    return this._civicCenterId;
  }

  public disable(): void {
    this._status = AdvertisementStatus.fromString('disabled');
  }

  public enable(): void {
    this._status = AdvertisementStatus.fromString('enabled');
  }

  public approve(): void {
    this._approvalStatus = AdvertisementApprovalStatus.fromString('approved');
  }
}
