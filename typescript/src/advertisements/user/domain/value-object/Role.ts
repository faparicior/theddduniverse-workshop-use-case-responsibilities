export class Role {
    public static readonly SUPERVISOR = new Role('supervisor');
    public static readonly ADMIN = new Role('admin');
    public static readonly MEMBER = new Role('member');

    private constructor(private readonly value: string) {}

    public static fromString(role: string): Role {
        switch (role) {
            case 'supervisor':
                return Role.SUPERVISOR;
            case 'admin':
                return Role.ADMIN;
            case 'member':
                return Role.MEMBER;
            default:
                throw new Error(`Invalid role: ${role}`);
        }
    }

    public getValue(): string {
        return this.value;
    }

    public isAdmin(): boolean {
        return this === Role.ADMIN;
    }
}
