export class DisableMemberCommand {

  constructor(
      public readonly securityUserId: string,
      public readonly securityUserRole: string,
      public readonly memberId: string,
  ) {
  }
}
