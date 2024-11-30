export class SignUpMemberCommand {
    constructor(
        public readonly securityUserId: string,
        public readonly securityUserRole: string,
        public readonly memberId: string,
        public readonly email: string,
        public readonly password: string,
        public readonly memberNumber: string,
        public readonly civicCenterId: string,
    ) {}
}
