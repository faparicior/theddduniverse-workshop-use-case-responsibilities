
export class AdvertisementDate {

    constructor(
        readonly _value: Date,
    ) {
    }

    public value(): Date {
        return this._value;
    }
}
