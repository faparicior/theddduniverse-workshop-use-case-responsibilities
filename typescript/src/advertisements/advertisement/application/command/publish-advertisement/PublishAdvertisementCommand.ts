export class PublishAdvertisementCommand {
  constructor(
      public readonly securityUserId: string,
      public readonly securityUserRole: string,
      public readonly id: string,
      public readonly description: string,
      public readonly email: string,
      public readonly password: string,
      public readonly memberNumber: string,
      public readonly civicCenterId: string
  ) {}
}
