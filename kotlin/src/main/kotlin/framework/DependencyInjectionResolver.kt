package framework

import advertisements.advertisement.application.command.publishAdvertisement.PublishAdvertisementUseCase
import advertisements.advertisement.application.command.renewAdvertisement.RenewAdvertisementUseCase
import advertisements.advertisement.application.command.updateAdvertisement.UpdateAdvertisementUseCase
import advertisements.advertisement.domain.AdvertisementRepository
import advertisements.advertisement.infrastructure.persistence.SqLiteAdvertisementRepository
import advertisements.advertisement.ui.http.PublishAdvertisementController
import advertisements.advertisement.ui.http.RenewAdvertisementController
import advertisements.advertisement.ui.http.UpdateAdvertisementController
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
                this.advertisementRepository()
            )
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

    fun deleteAdvertisementController(): Any{
        TODO("Not yet implemented")
    }
}