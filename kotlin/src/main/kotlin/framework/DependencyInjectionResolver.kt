package framework

import advertisements.advertisement.application.command.approveadvertisement.ApproveAdvertisementUseCase
import advertisements.advertisement.application.command.deleteadvertisement.DeleteAdvertisementUseCase
import advertisements.advertisement.application.command.disableadvertisement.DisableAdvertisementUseCase
import advertisements.advertisement.application.command.enableadvertisement.EnableAdvertisementUseCase
import advertisements.advertisement.application.command.publishadvertisement.PublishAdvertisementUseCase
import advertisements.advertisement.application.command.renewadvertisement.RenewAdvertisementUseCase
import advertisements.advertisement.application.command.updateadvertisement.UpdateAdvertisementUseCase
import advertisements.advertisement.domain.AdvertisementRepository
import advertisements.advertisement.infrastructure.persistence.SqLiteAdvertisementRepository
import advertisements.advertisement.ui.http.*
import advertisements.user.domain.UserRepository
import advertisements.user.infrastructure.persistence.SqliteUserRepository
import framework.database.DatabaseConnection
import framework.database.SqliteConnection
import framework.securityuser.FrameworkSecurityService
import framework.securityuser.SecurityUserRepository
import framework.securityuser.SqliteSecurityUserRepository

class DependencyInjectionResolver {
    fun publishAdvertisementController(): PublishAdvertisementController {
        return PublishAdvertisementController(
            PublishAdvertisementUseCase(
                this.advertisementRepository(),
                this.userRepository()
            ),
            this.securityService()
        )
    }

    fun updateAdvertisementController(): UpdateAdvertisementController {
        return UpdateAdvertisementController(
            UpdateAdvertisementUseCase(
                this.advertisementRepository()
            ),
            this.securityService()
        )
    }

    fun renewAdvertisementController(): RenewAdvertisementController {
        return RenewAdvertisementController(
            RenewAdvertisementUseCase(
                this.advertisementRepository(),
            ),
            this.securityService(),
        )
    }

    fun disableAdvertisementController(): DisableAdvertisementController {
        return DisableAdvertisementController(
            DisableAdvertisementUseCase(
                this.advertisementRepository(),
                this.userRepository(),
            ),
            this.securityService()
        )
    }

    fun enableAdvertisementController(): EnableAdvertisementController {
       return EnableAdvertisementController(
            EnableAdvertisementUseCase(
                this.advertisementRepository(),
                this.userRepository(),
            ),
            this.securityService()
        )
    }

    fun approveAdvertisementController(): ApproveAdvertisementController {
        return ApproveAdvertisementController(
            ApproveAdvertisementUseCase(
                this.advertisementRepository(),
                this.userRepository(),
            ),
            this.securityService()
        )
    }

    fun advertisementRepository(): AdvertisementRepository {
        return SqLiteAdvertisementRepository(
            this.connection()
        )
    }

    fun userRepository(): UserRepository {
        return SqliteUserRepository(
            this.connection()
        )
    }

    fun securityService(): FrameworkSecurityService {
        return FrameworkSecurityService(
            this.securityUserRepository()
        )
    }

    fun securityUserRepository(): SecurityUserRepository {
        return SqliteSecurityUserRepository(
            this.connection()
        )
    }

    fun connection(): DatabaseConnection {
        return SqliteConnection.getInstance()
    }

    fun deleteAdvertisementController(): DeleteAdvertisementController {
        return DeleteAdvertisementController(
            DeleteAdvertisementUseCase(
                this.advertisementRepository(),
                this.userRepository(),
            ),
            this.securityService()
        )
    }
}