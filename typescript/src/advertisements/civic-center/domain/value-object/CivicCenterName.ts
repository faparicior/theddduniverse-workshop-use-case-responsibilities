import {DescriptionTooLongException} from "../../../shared/exceptions/DescriptionTooLongException";
import {DescriptionEmptyException} from "../../../shared/exceptions/DescriptionEmptyException";

export class CivicCenterName {
    private readonly value: string;

    constructor(value: string) {
        this.validate(value);
        this.value = value;
    }

    public getValue(): string {
        return this.value;
    }

    private validate(value: string): void {
        if (value.length === 0) {
            throw DescriptionEmptyException.build();
        }

        if (value.length > 200) {
            throw DescriptionTooLongException.withLongitudeMessage(value);
        }
    }
}
