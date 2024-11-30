import { FrameworkRequest, Method } from "../../../../src/framework/FrameworkRequest"
import { FrameworkServer } from "../../../../src/framework/FrameworkServer"
import { SqliteConnectionFactory } from "../../../../src/framework/database/SqliteConnectionFactory"
import { DatabaseConnection } from "../../../../src/framework/database/DatabaseConnection"
import { createHash } from "node:crypto"
import {sprintf} from "sprintf-js";

let connection: DatabaseConnection
let server: FrameworkServer
const ID = '6fa00b21-2930-483e-b610-d6b0e5b19b29'
const ADVERTISEMENT_CREATION_DATE = '2024-02-03 13:30:23'
const DESCRIPTION = 'Dream advertisement'
const EMAIL = 'test@test.com'
const PASSWORD = 'myPassword'
const NEW_DESCRIPTION = 'Dream advertisement changed'
const INCORRECT_PASSWORD = 'myBadPassword'
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

    // TODO: Implement test

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

async function withAdminUser(status: string): Promise<void> {
    await connection.execute(
        `INSERT INTO users (id, email, password, role, member_number, civic_center_id, status) VALUES (?, ?, ?, ?, ?, ?, ?)`,
        [
            ADMIN_ID,
            'admin@test.com',
            createHash('md5').update('myPassword').digest('hex'),
            'admin',
            '123456',
            CIVIC_CENTER_ID,
            status
        ]
    );
}
