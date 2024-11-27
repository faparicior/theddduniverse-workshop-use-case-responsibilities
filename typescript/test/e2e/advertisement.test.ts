import { FrameworkRequest, Method } from "../../src/framework/FrameworkRequest"
import { FrameworkServer } from "../../src/framework/FrameworkServer"
import { SqliteConnectionFactory } from "../../src/framework/database/SqliteConnectionFactory"
import { DatabaseConnection } from "../../src/framework/database/DatabaseConnection"
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
const MEMBER_ID = 'e95a8999-cb23-4fa2-9923-e3015ef30411'
const CIVIC_CENTER_ID = '0d5a994b-1603-4c87-accc-581a59e4457c'

describe("Advertisement", () => {
    beforeAll(async () => {
        connection = await SqliteConnectionFactory.createClient()
        server = await FrameworkServer.start()
        await connection.execute('delete from advertisements', [])
    })

    beforeEach(async () => {
        await connection.execute('delete from advertisements', [])
        await connection.execute('delete from users', [])
    })

    it("Should publish an advertisement", async () => {

        await withMemberUser('enabled')

        const request = new FrameworkRequest(
            Method.POST,
            '/advertisement',
            {
                id: ID,
                description: DESCRIPTION,
                email: EMAIL,
                password: PASSWORD,
                civicCenterId: CIVIC_CENTER_ID,
                memberNumber: MEMBER_ID
            },
            {
                userSession: MEMBER_ID
            }
        )

        const response = await server.route(request)

        expect(response.statusCode).toBe(201)
        expect(response.body).toEqual(successResponse(201))

        const dbData = await connection.query("SELECT * FROM advertisements") as any[]

        expect(dbData.length).toBe(1)
        expect(dbData[0].id).toBe(ID)
        expect(dbData[0].description).toBe(DESCRIPTION)
        expect(dbData[0].password).toBeDefined
        expect(dbData[0].advertisement_date).toBeDefined
    })

    it("Should fail publishing an advertisement with an existing id", async () => {

        await withMemberUser('enabled')

        const request = new FrameworkRequest(
            Method.POST,
            '/advertisement',
            {
                id: ID,
                description: DESCRIPTION,
                email: EMAIL,
                password: PASSWORD,
                civicCenterId: CIVIC_CENTER_ID,
                memberNumber: MEMBER_ID
            },
            {
                userSession: MEMBER_ID
            }
        )

        const response = await server.route(request)
        expect(response.statusCode).toBe(201)

        const response2 = await server.route(request)
        expect(response2.statusCode).toBe(400)
        expect(response2.body).toEqual(errorCommandResponse(400, sprintf('Advertisement with Id %s already exists', ID)))
    })

    it("Should change an advertisement", async () => {
        await withMemberUser('enabled')
        await withAnAdvertisementCreated('enabled', 'approved')

        const request = new FrameworkRequest(
            Method.PUT,
            `/advertisement/${ID}`,
            {
                description: NEW_DESCRIPTION,
                email: EMAIL,
                password: PASSWORD,
            },
            {
                userSession: MEMBER_ID
            }
        )

        const response = await server.route(request)

        expect(response.statusCode).toBe(200)
        expect(response.body).toEqual(successResponse(200))

        const dbData = await connection.query("SELECT * FROM advertisements") as any[]

        expect(dbData.length).toBe(1)
        expect(dbData[0].description).toBe(NEW_DESCRIPTION)
        const newDate = new Date(dbData[0].advertisement_date)
        const diff = getHourDifference(newDate)
        expect(diff).toBeLessThan(1)
    })

    it("Should fail changing an non existent advertisement", async () => {
        await withMemberUser('enabled')

        const request = new FrameworkRequest(
            Method.PUT,
            `/advertisement/${ID}`,
            {
                description: NEW_DESCRIPTION,
                email: EMAIL,
                password: PASSWORD,
            },
            {
                userSession: MEMBER_ID
            }
        )

        const response = await server.route(request)

        expect(response.statusCode).toBe(404)
        expect(response.body).toEqual(errorCommandResponse(404, sprintf('Advertisement not found with Id: %s', ID)))
    })

    it("Should renew an advertisement", async () => {
        await withAnAdvertisementCreated()

        const request = new FrameworkRequest(Method.PATCH, `/advertisement/${ID}`,
            { password: PASSWORD },
            {}
        )

        const response = await server.route(request)

        expect(response.statusCode).toBe(200)
        expect(response.body).toEqual(successResponse(200))

        const dbData = await connection.query("SELECT * FROM advertisements") as any[]

        expect(dbData.length).toBe(1)
        const newDate = new Date(dbData[0].advertisement_date)
        const diff = getHourDifference(newDate)
        expect(diff).toBeLessThan(1)
    })

    it("Should fail renewing an non existent advertisement", async () => {
        const request = new FrameworkRequest(Method.PATCH, `/advertisement/${ID}`,
            { password: PASSWORD },
            {}
        )

        const response = await server.route(request)

        expect(response.statusCode).toBe(404)
        expect(response.body).toEqual(errorCommandResponse(404, sprintf('Advertisement not found with Id: %s', ID)))
    })

    // it("Should not change an advertisement with incorrect password", async () => {
    //     await withAnAdvertisementCreated()
    //
    //     const request = new FrameworkRequest(Method.PUT, `/advertisements/${ID}`,
    //         { description: NEW_DESCRIPTION, email: EMAIL, password: INCORRECT_PASSWORD }
    //     )
    //
    //     const response = await server.route(request)
    //
    //     expect(response.statusCode).toBe(400)
    //     expect(response.body).toEqual(errorCommandResponse(400, 'Invalid password'))
    //
    //     const dbData = await connection.query("SELECT * FROM advertisements") as any[]
    //
    //     expect(dbData.length).toBe(1)
    //     expect(dbData[0].description).toBe(DESCRIPTION)
    //     const newDate = new Date(dbData[0].advertisement_date)
    //     const diff = getHourDifference(newDate)
    //     expect(diff).toBeGreaterThan(1)
    // })
    //
    // it("Should not renew an advertisement with incorrect password", async () => {
    //     await withAnAdvertisementCreated()
    //
    //     const request = new FrameworkRequest(Method.PATCH, `/advertisements/${ID}`,
    //         { password: INCORRECT_PASSWORD }
    //     )
    //
    //     const response = await server.route(request)
    //
    //     expect(response.statusCode).toBe(400)
    //     expect(response.body).toEqual(errorCommandResponse(400, 'Invalid password'))
    //
    //     const dbData = await connection.query("SELECT * FROM advertisements") as any[]
    //
    //     expect(dbData.length).toBe(1)
    //     expect(dbData[0].description).toBe(DESCRIPTION)
    //     const newDate = new Date(dbData[0].advertisement_date)
    //     const diff = getHourDifference(newDate)
    //     expect(diff).toBeGreaterThan(1)
    // })

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

function getHourDifference(date: Date): number {

    const currentDate = new Date()
    const differenceInMs = currentDate.getTime() - date.getTime()
    const differenceInHours = differenceInMs / (1000 * 60 * 60)
    return differenceInHours
}
