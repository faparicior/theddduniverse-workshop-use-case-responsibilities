package framework

class Server(private val resolver: DependencyInjectionResolver){
    fun route(request: FrameworkRequest): FrameworkResponse {
        return when (request.method) {
            FrameworkRequest.METHOD_GET -> {
                this.get()
            }
            FrameworkRequest.METHOD_POST -> {
                this.post(request)
            }
            FrameworkRequest.METHOD_PUT -> {
                this.put(request)
            }
            FrameworkRequest.METHOD_PATCH -> {
                this.patch(request)
            }
            FrameworkRequest.METHOD_DELETE -> {
                this.delete(request)
            }
            else -> {
               return this.notFound()
            }
        }
    }

    private fun get(): FrameworkResponse {
        return FrameworkResponse(FrameworkResponse.STATUS_NOT_FOUND, mapOf())
    }

    private fun post(request: FrameworkRequest): FrameworkResponse {
        return when (request.path) {
            "advertisements" -> resolver.publishAdvertisementController().execute(request)
            "members/signup" -> resolver.signUpMemberController().execute(request)
            else -> this.notFound()
        }
    }

    private fun put(request: FrameworkRequest): FrameworkResponse {
        val match = when {
            Regex("^members/([0-9a-fA-F\\-]+)/disable$").find(request.path) != null -> {
                resolver.disableMemberController().execute(request, mapOf("memberId" to Regex("^members/([0-9a-fA-F\\-]+)/disable$").find(request.path)!!.groupValues[1]))
            }
            Regex("^members/([0-9a-fA-F\\-]+)/enable$").find(request.path) != null -> {
                resolver.enableMemberController().execute(request, mapOf("memberId" to Regex("^members/([0-9a-fA-F\\-]+)/enable$").find(request.path)!!.groupValues[1]))
            }
            Regex("^advertisements/([0-9a-fA-F\\-]+)/disable$").find(request.path) != null -> {
                resolver.disableAdvertisementController().execute(request, mapOf("advertisementId" to Regex("^advertisements/([0-9a-fA-F\\-]+)/disable$").find(request.path)!!.groupValues[1]))
            }
            Regex("^advertisements/([0-9a-fA-F\\-]+)/enable$").find(request.path) != null -> {
                resolver.enableAdvertisementController().execute(request, mapOf("advertisementId" to Regex("^advertisements/([0-9a-fA-F\\-]+)/enable$").find(request.path)!!.groupValues[1]))
            }
            Regex("^advertisements/([0-9a-fA-F\\-]+)/approve$").find(request.path) != null -> {
                resolver.approveAdvertisementController().execute(request, mapOf("advertisementId" to Regex("^advertisements/([0-9a-fA-F\\-]+)/approve$").find(request.path)!!.groupValues[1]))
            }
            Regex("^advertisements/([0-9a-fA-F\\-]+)$").find(request.path) != null -> {
                resolver.updateAdvertisementController().execute(request, mapOf("advertisementId" to Regex("^advertisements/([0-9a-fA-F\\-]+)$").find(request.path)!!.groupValues[1]))
            }
            else -> null
        }

        if (match is FrameworkResponse) {
            return match
        }

        return this.notFound()
    }

    private fun patch(request: FrameworkRequest): FrameworkResponse {
        return when (request.pathStart()) {
            "advertisements" -> {
                resolver.renewAdvertisementController().execute(request)
            }
            else -> {
                this.notFound()
            }
        }
    }

    private fun delete(request: FrameworkRequest): FrameworkResponse {
        return when (request.pathStart()) {
            "advertisements" -> resolver.deleteAdvertisementController().execute(request, mapOf("advertisementId" to request.getIdPath()))
            else -> this.notFound()
        }
    }

    private fun notFound(): FrameworkResponse {
        return FrameworkResponse(FrameworkResponse.STATUS_NOT_FOUND, mapOf())
    }
}
