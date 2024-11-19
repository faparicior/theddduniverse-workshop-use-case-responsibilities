<?php
declare(strict_types=1);

namespace Demo\App\Framework;

final class Server
{
    public function __construct(private DependencyInjectionResolver $resolver)
    {
    }

    public function route(FrameworkRequest $request): FrameworkResponse
    {
        return match ($request->method()) {
            FrameworkRequest::METHOD_GET => $this->get($request),
            FrameworkRequest::METHOD_POST => $this->post($request),
            FrameworkRequest::METHOD_PUT => $this->put($request),
            FrameworkRequest::METHOD_PATCH => $this->patch($request),
            FrameworkRequest::METHOD_DELETE => $this->delete($request),
            default => $this->notFound($request),
        };
    }

    public function get(FrameworkRequest $request): FrameworkResponse
    {
        return $this->notFound($request);
    }

    public function post(FrameworkRequest $request): FrameworkResponse
    {
        return match ($request->path()) {
            'advertisement' => $this->resolver->publishAdvertisementController()->request($request),
            'member/signup' => $this->resolver->signUpMemberController()->request($request),
            default => $this->notFound($request),
        };
    }

    public function put(FrameworkRequest $request): FrameworkResponse
    {
        $match = match ($request->pathStart()) {
            'advertisements' => $this->resolver->updateAdvertisementController()->request($request, ['advertisementId' => $request->getIdPath()]),
            default => null,
        };

        if($match instanceof FrameworkResponse) {
            return $match;
        }

        $match = match (1) {
            preg_match('/^member\/([0-9a-fA-F\-]+)\/disable$/', $request->path(), $matches) =>
                $this->resolver->disableMemberController()->request($request, ['memberId' => $matches[1]]),
            preg_match('/^member\/([0-9a-fA-F\-]+)\/enable$/', $request->path(), $matches) =>
                $this->resolver->enableMemberController()->request($request, ['memberId' => $matches[1]]),
            preg_match('/^advertisements\/([0-9a-fA-F\-]+)\/disable$/', $request->path(), $matches) =>
                $this->resolver->disableAdvertisementController()->request($request, ['advertisementId' => $matches[1]]),
            preg_match('/^advertisements\/([0-9a-fA-F\-]+)\/enable$/', $request->path(), $matches) =>
                $this->resolver->enableAdvertisementController()->request($request, ['advertisementId' => $matches[1]]),
            preg_match('/^advertisements\/([0-9a-fA-F\-]+)\/approve$/', $request->path(), $matches) =>
                $this->resolver->approveAdvertisementController()->request($request, ['advertisementId' => $matches[1]]),
            default => null,
        };

        if($match instanceof FrameworkResponse) {
            return $match;
        }

        return $this->notFound($request);
    }

    public function patch(FrameworkRequest $request): FrameworkResponse
    {
        return match ($request->pathStart()) {
            'advertisements' => $this->resolver->renewAdvertisementController()->request($request, ['advertisementId' => $request->getIdPath()]),
            default => $this->notFound($request),
        };
    }

    public function delete(FrameworkRequest $request): FrameworkResponse
    {
        return match ($request->pathStart()) {
            'advertisements' => $this->resolver->deleteAdvertisementController()->request($request, ['advertisementId' => $request->getIdPath()]),
            default => $this->notFound($request),
        };
    }

    public function notFound(FrameworkRequest $request): FrameworkResponse
    {
        return new FrameworkResponse(FrameworkResponse::STATUS_NOT_FOUND, []);
    }
}
