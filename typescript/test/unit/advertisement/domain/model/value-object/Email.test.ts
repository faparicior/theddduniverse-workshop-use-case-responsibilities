import {Email} from "../../../../../../src/advertisements/advertisement/domain/value-object/Email";


describe("Advertisement password", () => {

    const EMAIL = "test@test.com";
    const INVALID_EMAIL = "test.test.com";

    beforeAll(async () => {
    })

    beforeEach(async () => {
    })

    it("Should be created with a valid email", async () => {
        let email = new Email(EMAIL);

        expect(email.value()).toBe(EMAIL);
    });

    it("Should throw an exception when email has invalid format", async () => {
        expect(() => {
            new Email(INVALID_EMAIL);
        }).toThrow("Invalid email format");
    });
});
