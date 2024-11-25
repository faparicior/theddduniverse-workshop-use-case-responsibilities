import {InvalidUniqueIdentifierException} from "../exceptions/InvalidUniqueIdentifierException";

export class AdvertisementId {

    constructor(
        readonly _value: string,
    ) {
        this.validate(_value);
    }

    private validate(value: string) {
        const uuidRegex = /^[0-9a-f]{8}-[0-9a-f]{4}-[4][0-9a-f]{3}-[89ab][0-9a-f]{3}-[0-9a-f]{12}$/i;

        if (!uuidRegex.test(value)) {
            throw InvalidUniqueIdentifierException.withId(value);
        }
    }

    public value(): string {
        return this._value;
    }
}
