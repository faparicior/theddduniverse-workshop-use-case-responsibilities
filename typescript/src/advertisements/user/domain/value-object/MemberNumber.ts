export class MemberNumber {
    private readonly _value: string;

    constructor(value: string) {
        this._value = value;
    }

    public value(): string {
        return this._value;
    }
}
