export class PublishAdvertisementCommand {

  constructor(
    public readonly id: string,
    public readonly description: string,
    public readonly email: string,
    public readonly password: string
  ) {
  }

}
