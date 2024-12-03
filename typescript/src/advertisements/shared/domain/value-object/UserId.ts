import {InvalidUniqueIdentifierException} from "./CivicCenterId";

export class UserId {
    private readonly _value: string;

    constructor(value: string) {
        if (!this.validate(value)) {
            throw new InvalidUniqueIdentifierException(`Invalid ID: ${value}`);
        }
        this._value = value;
    }

    public value(): string {
        return this._value;
    }

    private validate(value: string): boolean {
        const uuidRegex = /^[0-9A-F]{8}-[0-9A-F]{4}-4[0-9A-F]{3}-[89AB][0-9A-F]{3}-[0-9A-F]{12}$/i;
        return uuidRegex.test(value);
    }

    public equals(userId: UserId): boolean {
        return this._value === userId._value;
    }
}
