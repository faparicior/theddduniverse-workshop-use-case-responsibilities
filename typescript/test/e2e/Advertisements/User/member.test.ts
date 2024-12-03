import {SqliteConnectionFactory} from "../../../../src/framework/database/SqliteConnectionFactory";
import {FrameworkServer} from "../../../../src/framework/FrameworkServer";
import {FrameworkRequest, Method} from "../../../../src/framework/FrameworkRequest";
import {createHash} from "node:crypto";
import {DatabaseConnection} from "../../../../src/framework/database/DatabaseConnection";

let connection: DatabaseConnection
let server: FrameworkServer

const MEMBER_ID = '6fa00b21-2930-483e-b610-d6b0e5b19b29'
const ADMIN_ID = 'e95a8999-cb23-4fa2-9923-e3015ef30411'
const EMAIL = 'test@test.com'
const PASSWORD = 'myPassword'
const CIVIC_CENTER_ID = '0d5a994b-1603-4c87-accc-581a59e4457c'
const CIVIC_CENTER_2_ID = '5ddd994b-1603-4c87-accc-581a59e4457c'

describe("Member", () => {
    beforeAll(async () => {
        connection = await SqliteConnectionFactory.createClient()
        server = await FrameworkServer.start()
        await connection.execute('delete from advertisements', [])
    })

    beforeEach(async () => {
        await connection.execute('delete from advertisements', [])
        await connection.execute('delete from users', [])
    })

    // TODO: Enable and disable by admin
    it("Should signup a member as admin", async () => {
        await withAdminUser()

        const request = new FrameworkRequest(Method.POST, `/member/signup`,
            {
                memberId: MEMBER_ID,
                email: EMAIL,
                password: PASSWORD,
                memberNumber: '123456',
                civicCenterId: CIVIC_CENTER_ID
            },
            {'userSession': ADMIN_ID}
        )

        const response = await server.route(request)

        expect(response.statusCode).toBe(201)

        const dbData = await connection.query(`SELECT *
                                               FROM users
                                               where id = '${MEMBER_ID}'`) as any[]

        expect(dbData.length).toBe(1)
        expect(dbData[0].member_number).toBe('123456')
    })

    it("Should disable a member as admin", async () => {
        await withAdminUser()
        await withMemberUser('enabled')

        const request = new FrameworkRequest(Method.PUT, `/member/${MEMBER_ID}/disable`,
            {},
            {'userSession': ADMIN_ID}
        )

        const response = await server.route(request)

        expect(response.statusCode).toBe(200)

        const dbData = await connection.query(`SELECT *
                                               FROM users
                                               where id = '${MEMBER_ID}'`) as any[]

        expect(dbData.length).toBe(1)
        expect(dbData[0].status).toBe('disabled')
    })

    it("Should enable a member as admin", async () => {
        await withAdminUser()
        await withMemberUser('disabled')

        const request = new FrameworkRequest(Method.PUT, `/member/${MEMBER_ID}/enable`,
            {},
            {'userSession': ADMIN_ID}
        )

        const response = await server.route(request)

        expect(response.statusCode).toBe(200)

        const dbData = await connection.query(`SELECT *
                                               FROM users
                                               where id = '${MEMBER_ID}'`) as any[]

        expect(dbData.length).toBe(1)
        expect(dbData[0].status).toBe('enabled')
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