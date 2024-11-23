package framework

import advertisements.advertisement.application.command.publishAdvertisement.PublishAdvertisementUseCase
import advertisements.advertisement.application.command.renewAdvertisement.RenewAdvertisementUseCase
import advertisements.advertisement.application.command.updateAdvertisement.UpdateAdvertisementUseCase
import advertisements.advertisement.domain.AdvertisementRepository
import advertisements.advertisement.infrastructure.persistence.SqLiteAdvertisementRepository
import advertisements.advertisement.ui.http.PublishAdvertisementController
import advertisements.advertisement.ui.http.RenewAdvertisementController
import advertisements.advertisement.ui.http.UpdateAdvertisementController
import framework.database.DatabaseConnection
import framework.database.SqliteConnection

class DependencyInjectionResolver {
    fun publishAdvertisementController(): PublishAdvertisementController {
        return PublishAdvertisementController(
            PublishAdvertisementUseCase(
                this.advertisementRepository()
            )
        )
    }

    fun updateAdvertisementController(): UpdateAdvertisementController {
        return UpdateAdvertisementController(
            UpdateAdvertisementUseCase(
                this.advertisementRepository()
            )
        )
    }

    fun renewAdvertisementController(): advertisements.advertisement.ui.http.RenewAdvertisementController {
        return advertisements.advertisement.ui.http.RenewAdvertisementController(
            RenewAdvertisementUseCase(
                this.advertisementRepository()
            )
        )
    }

    fun advertisementRepository(): advertisements.advertisement.domain.AdvertisementRepository {
        return SqLiteAdvertisementRepository(
            this.connection()
        )
    }

    fun connection(): DatabaseConnection {
        return SqliteConnection.getInstance()
    }
}