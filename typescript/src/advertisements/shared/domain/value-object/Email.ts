import {InvalidEmailFormatException} from "../../../advertisement/domain/exceptions/InvalidEmailFormatException";

export class Email {
    private static readonly EMAIL_REGEX = /^[a-zA-Z0-9.!#$%&'*+/=?^_`{|}~-]+@[a-zA-Z0-9](?:[a-zA-Z0-9-]{0,61}[a-zA-Z0-9])?(?:\.[a-zA-Z0-9](?:[a-zA-Z0-9-]{0,61}[a-zA-Z0-9])?)*$/;

    constructor(
        readonly _value: string,
    ) {
        this.validate(_value);
    }

    private validate(value: string) {
        if (!Email.EMAIL_REGEX.test(value)) {
            throw InvalidEmailFormatException.withEmail(value);
        }
    }

    public value(): string {
        return this._value;
    }
}
