export class DisableAdvertisementCommand {

  constructor(
      public readonly securityUserId: string,
      public readonly securityUserRole: string,
      public readonly advertisementId: string,
  ) {
  }

}
