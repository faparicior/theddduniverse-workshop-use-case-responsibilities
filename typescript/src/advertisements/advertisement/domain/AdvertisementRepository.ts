import { Advertisement } from "./Advertisement";
import {AdvertisementId} from "./value-object/AdvertisementId";

export interface AdvertisementRepository {

  save(name: Advertisement): Promise<void>;

  findById(id: AdvertisementId): Promise<Advertisement | null>;
}
