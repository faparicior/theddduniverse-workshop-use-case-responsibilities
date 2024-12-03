import {DescriptionEmptyException} from "../../../shared/exceptions/DescriptionEmptyException";
import {DescriptionTooLongException} from "../../../shared/exceptions/DescriptionTooLongException";

export class Description {

    constructor(
        readonly _value: string,
    ) {
        this.validate(_value);
    }

    private validate(value: string) {
        if (value.length === 0) {
            throw DescriptionEmptyException.build();
        }

        if (value.length > 200) {
            throw DescriptionTooLongException.withLongitudeMessage(this._value);
        }
    }

    public value(): string {
        return this._value;
    }
}
