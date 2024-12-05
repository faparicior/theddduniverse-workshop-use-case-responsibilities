import { AdvertisementRepository } from '../../domain/AdvertisementRepository';
import { Advertisement } from '../../domain/Advertisement';
import { DatabaseConnection } from '../../../../framework/database/DatabaseConnection';
import {Password} from "../../../shared/domain/value-object/Password";
import {AdvertisementDate} from "../../domain/value-object/AdvertisementDate";
import {Description} from "../../domain/value-object/Description";
import {AdvertisementId} from "../../domain/value-object/AdvertisementId";
import {Email} from "../../../shared/domain/value-object/Email";
import {CivicCenterId} from "../../../shared/domain/value-object/CivicCenterId";
import {UserId} from "../../../shared/domain/value-object/UserId";
import {MemberUser} from "../../../user/domain/MemberUser";
import {ActiveAdvertisements} from "../../domain/value-object/ActiveAdvertisements";

export class SqliteAdvertisementRepository implements AdvertisementRepository {

  constructor(
    private connection: DatabaseConnection) {
  }

  async findById(id: AdvertisementId): Promise<Advertisement | null> {

    const result = await this.connection.query(`SELECT * FROM advertisements WHERE id = ? `, [id.value()])

    if (!result || result.length < 1) {
      return null
    }

    const row = result[0] as any;
    return new Advertisement(
      new AdvertisementId(row.id),
      new Description(row.description),
      new Email(row.email),
      Password.fromEncryptedPassword(row.password),
      new AdvertisementDate(new Date(row.advertisement_date)),
      new CivicCenterId(row.civic_center_id),
      new UserId(row.user_id)
    )
  }

  async activeAdvertisementsByMember(member: MemberUser): Promise<ActiveAdvertisements> {
    const result = await this.connection.query(`SELECT * FROM advertisements WHERE user_id = ? AND status = 'active'`, [member.id().value()])

    return ActiveAdvertisements.fromInt(result.length)
  }

  async save(advertisement: Advertisement): Promise<void> {

    await this.connection.execute(
      `INSERT INTO advertisements (id, description, email, password, advertisement_date, civic_center_id, user_id, status, approval_status) 
      VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?) 
      ON CONFLICT(id) DO UPDATE 
      SET description = excluded.description, password = excluded.password, advertisement_date = excluded.advertisement_date, civic_center_id = excluded.civic_center_id, user_id = excluded.user_id, status = excluded.status, approval_status = excluded.approval_status`, [
      advertisement.id().value(),
      advertisement.description().value(),
      advertisement.email().value(),
      advertisement.password().value(),
      advertisement.date().value().toISOString(),
      advertisement.civicCenterId().value(),
      advertisement.memberId().value(),
      advertisement.status().value(),
      advertisement.approvalStatus().value(),
    ]);
  }

  delete(advertisement: Advertisement): Promise<void> {
      return this.connection.execute(`DELETE FROM advertisements WHERE id = ?`, [advertisement.id().value()])
  }
}
