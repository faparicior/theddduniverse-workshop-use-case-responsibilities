export class ActiveAdvertisements {
    private constructor(private readonly activeAdvertisements: number) {}

    public static fromInt(activeAdvertisements: number): ActiveAdvertisements {
        return new ActiveAdvertisements(activeAdvertisements);
    }

    public value(): number {
        return this.activeAdvertisements;
    }
}
