import { CivicCenterName } from './value-object/CivicCenterName';
import {CivicCenterId} from "../../shared/domain/value-object/CivicCenterId";

export class CivicCenter {
    constructor(
        private readonly id: CivicCenterId,
        private name: CivicCenterName
    ) {}

    public getId(): CivicCenterId {
        return this.id;
    }

    public getName(): CivicCenterName {
        return this.name;
    }
}
