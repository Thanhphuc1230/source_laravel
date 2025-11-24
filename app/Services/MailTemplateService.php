<?php

namespace App\Services;

use App\Repositories\MailTemplateRepositoryInterface;
use App\Services\CacheService;

class MailTemplateService
{
    protected $repository;

    public function __construct(MailTemplateRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    public function getAll()
    {
        return CacheService::remember('mail_templates', 'all', CacheService::getTtl('medium'), function () {
            return $this->repository->getAll();
        });
    }

    public function getById($id)
    {
        return CacheService::remember('mail_templates', "id_{$id}", CacheService::getTtl('medium'), function () use ($id) {
            return $this->repository->getById($id);
        });
    }

    public function getByType($type)
    {
        return CacheService::remember('mail_templates', "type_{$type}", CacheService::getTtl('medium'), function () use ($type) {
            return $this->repository->getByType($type);
        });
    }

    public function getActiveByType($type)
    {
        return CacheService::remember('mail_templates', "active_type_{$type}", CacheService::getTtl('short'), function () use ($type) {
            return $this->repository->getActiveByType($type);
        });
    }

    public function create(array $data)
    {
        $template = $this->repository->create($data);
        CacheService::forgetTag('mail_templates');
        return $template;
    }

    public function update($id, array $data)
    {
        $template = $this->repository->update($id, $data);
        CacheService::forgetTag('mail_templates');
        return $template;
    }

    public function delete($id)
    {
        $result = $this->repository->delete($id);
        CacheService::forgetTag('mail_templates');
        return $result;
    }

    public function paginate($perPage = 15)
    {
        return $this->repository->paginate($perPage);
    }

    public function clearCache()
    {
        return CacheService::forgetTag('mail_templates');
    }
}