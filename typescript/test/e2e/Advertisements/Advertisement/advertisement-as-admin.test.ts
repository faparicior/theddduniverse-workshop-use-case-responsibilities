import { FrameworkRequest, Method } from "../../../../src/framework/FrameworkRequest"
import { FrameworkServer } from "../../../../src/framework/FrameworkServer"
import { SqliteConnectionFactory } from "../../../../src/framework/database/SqliteConnectionFactory"
import { DatabaseConnection } from "../../../../src/framework/database/DatabaseConnection"
import { createHash } from "node:crypto"

let connection: DatabaseConnection
let server: FrameworkServer
const ID = '6fa00b21-2930-483e-b610-d6b0e5b19b29'
const ADVERTISEMENT_CREATION_DATE = '2024-02-03 13:30:23'
const DESCRIPTION = 'Dream advertisement'
const EMAIL = 'test@test.com'
const PASSWORD = 'myPassword'
const ADMIN_ID = '91b5fa8c-6212-4c0f-862f-4dc1cb0472c4'
const MEMBER_ID = 'e95a8999-cb23-4fa2-9923-e3015ef30411'
const CIVIC_CENTER_ID = '0d5a994b-1603-4c87-accc-581a59e4457c'

describe("Advertisement as admin", () => {
    beforeAll(async () => {
        connection = await SqliteConnectionFactory.createClient()
        server = await FrameworkServer.start()
        await connection.execute('delete from advertisements', [])
    })

    beforeEach(async () => {
        await connection.execute('delete from advertisements', [])
        await connection.execute('delete from users', [])
    })

    // TODO: Sign up, enable disable member

    it("Should approve an advertisement as admin", async () => {
        await withAdminUser()
        await withMemberUser('enabled')
        await withAnAdvertisementCreated('disabled', 'pending_for_approval')

        const request = new FrameworkRequest(Method.PUT, `/advertisement/${ID}/approve`,
            {},
            { 'userSession': ADMIN_ID }
        )

        const response = await server.route(request)

        expect(response.statusCode).toBe(200)

        const dbData = await connection.query("SELECT * FROM advertisements") as any[]

        expect(dbData.length).toBe(1)
        expect(dbData[0].approval_status).toBe('approved')
    })

    it("Should enable an advertisement as admin", async () => {
        await withAdminUser()
        await withMemberUser('enabled')
        await withAnAdvertisementCreated('disabled')

        const request = new FrameworkRequest(Method.PUT, `/advertisement/${ID}/enable`,
            {},
            { 'userSession': ADMIN_ID }
        )

        const response = await server.route(request)

        expect(response.statusCode).toBe(200)

        const dbData = await connection.query("SELECT * FROM advertisements") as any[]

        expect(dbData.length).toBe(1)
        expect(dbData[0].status).toBe('enabled')
    })

    it("Should disable an advertisement as admin", async () => {
        await withAdminUser()
        await withMemberUser('enabled')
        await withAnAdvertisementCreated('enabled')

        const request = new FrameworkRequest(Method.PUT, `/advertisement/${ID}/disable`,
            {},
            { 'userSession': ADMIN_ID }
        )

        const response = await server.route(request)

        expect(response.statusCode).toBe(200)

        const dbData = await connection.query("SELECT * FROM advertisements") as any[]

        expect(dbData.length).toBe(1)
        expect(dbData[0].status).toBe('disabled')
    })
})

function errorCommandResponse(code: number = 400, message: string = '') {
    return {
        errors: message,
        code,
        message: message,
    }
}

function successResponse(code: number = 200) {
    return {
        errors: '',
        code,
        message: '',
    }
}

async function withAnAdvertisementCreated(status: string = 'enabled', approvalStatus: string = 'approved'): Promise<void> {
    await connection.execute(
        `INSERT INTO advertisements (id, description, email, password, advertisement_date, status, approval_status, user_id, civic_center_id) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)`,
        [
            ID,
            DESCRIPTION,
            EMAIL,
            createHash('md5').update(PASSWORD).digest('hex'),
            ADVERTISEMENT_CREATION_DATE,
            status,
            approvalStatus,
            MEMBER_ID,
            CIVIC_CENTER_ID
        ])
}

async function withMemberUser(status: string): Promise<void> {
    await connection.execute(
        `INSERT INTO users (id, email, password, role, member_number, civic_center_id, status) VALUES (?, ?, ?, ?, ?, ?, ?)`,
        [
            MEMBER_ID,
            'member@test.com',
            createHash('md5').update('myPassword').digest('hex'),
            'member',
            '123456',
            CIVIC_CENTER_ID,
            status
        ]
    );
}

async function withAdminUser(): Promise<void> {
    await connection.execute(
        `INSERT INTO users (id, email, password, role, member_number, civic_center_id, status) VALUES (?, ?, ?, ?, ?, ?, ?)`,
        [
            ADMIN_ID,
            'admin@test.com',
            createHash('md5').update('myPassword').digest('hex'),
            'admin',
            '',
            CIVIC_CENTER_ID,
            'enabled'
        ]
    );
}