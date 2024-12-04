import { Advertisement } from "./Advertisement";
import {AdvertisementId} from "./value-object/AdvertisementId";
import {MemberNumber} from "../../user/domain/value-object/MemberNumber";
import {MemberUser} from "../../user/domain/MemberUser";
import {ActiveAdvertisements} from "./value-object/ActiveAdvertisements";

export interface AdvertisementRepository {

  save(name: Advertisement): Promise<void>;

  findById(id: AdvertisementId): Promise<Advertisement | null>;

  activeAdvertisementsByMember(member: MemberUser): Promise<ActiveAdvertisements>;

  delete(advertisement: Advertisement): Promise<void>;
}
