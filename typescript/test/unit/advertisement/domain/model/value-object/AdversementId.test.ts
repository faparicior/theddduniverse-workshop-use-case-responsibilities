import {AdvertisementId} from "../../../../../../src/advertisements/advertisement/domain/value-object/AdvertisementId";


describe("Advertisement password", () => {

    const VALID_UUID = "a98e8d18-ebb8-4b34-acb4-8977bfdad42f"
    const INVALID_UUID = "a98e8d18-ebb8-9999-acb4-8977bfdad42f"

    beforeAll(async () => {
    })

    beforeEach(async () => {
    })

    it("Should be created with a valid unique identifier", async () => {
        let advertisementId = new AdvertisementId(VALID_UUID);

        // expect value
        expect(advertisementId.value()).toBe(VALID_UUID);
    });

    it("Should throw an exception when has not uuid 4 standards", async () => {
        expect(() => {
            new AdvertisementId(INVALID_UUID);
        }).toThrow("Invalid unique identifier format for " + INVALID_UUID);
    });
});
