import {Description} from "../../../../../../src/advertisements/advertisement/domain/value-object/Description";


describe("Advertisement password", () => {

    const DESCRIPTION = "my description";

    beforeAll(async () => {
    })

    beforeEach(async () => {
    })

    it("Should be created with a valid unique description", async () => {
        let description = new Description(DESCRIPTION);

        expect(description.value()).toBe(DESCRIPTION);
    });

    it("Should throw an exception when is empty", async () => {
        expect(() => {
            new Description("");
        }).toThrow("Description empty");
    });

    it("Should throw an exception when hs more than 200 characters", async () => {
        expect(() => {
            let rep = "a".repeat(201);
            new Description(rep);
        }).toThrow("Description has more than 200 characters: Has 201 characters");
    });
});
