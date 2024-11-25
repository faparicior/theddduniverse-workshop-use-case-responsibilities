import { AdvertisementRepository } from '../../domain/AdvertisementRepository';
import { Advertisement } from '../../domain/Advertisement';
import { DatabaseConnection } from '../../../../framework/database/DatabaseConnection';
import {Password} from "../../../shared/value-object/Password";
import {AdvertisementDate} from "../../domain/value-object/AdvertisementDate";
import {Description} from "../../domain/value-object/Description";
import {AdvertisementId} from "../../domain/value-object/AdvertisementId";

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
      Password.fromEncryptedPassword(row.password),
      new AdvertisementDate(new Date(row.advertisement_date))
    )

  }

  async save(advertisement: Advertisement): Promise<void> {

    await this.connection.execute(
      `INSERT INTO advertisements (id, description, password, advertisement_date) 
      VALUES (?, ?, ?, ?) 
      ON CONFLICT(id) DO UPDATE 
      SET description = excluded.description, password = excluded.password, advertisement_date = excluded.advertisement_date`, [
      advertisement.id().value(),
      advertisement.description().value(),
      advertisement.password().value(),
      advertisement.date().value().toISOString(),
    ]);
  }
}
