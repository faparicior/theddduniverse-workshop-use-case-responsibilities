import { Advertisement } from "./Advertisement";
import {AdvertisementId} from "./value-object/AdvertisementId";
import {UserId} from "../../shared/domain/value-object/UserId";
import {ActiveAdvertisements} from "./value-object/ActiveAdvertisements";

export interface AdvertisementRepository {

  save(name: Advertisement): Promise<void>;

  findById(id: AdvertisementId): Promise<Advertisement | null>;

  delete(advertisement: Advertisement): Promise<void>;

  activeAdvertisementsByMemberId(memberId: UserId): Promise<ActiveAdvertisements>;
}
