<?php

namespace App\Services;

use App\Repositories\MailTemplateRepositoryInterface;

class MailTemplateService
{
    protected $repository;

    public function __construct(MailTemplateRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    public function getAll()
    {
        return $this->repository->getAll();
    }

    public function getById($id)
    {
        return $this->repository->getById($id);
    }

    public function getByType($type)
    {
        return $this->repository->getByType($type);
    }

    public function getActiveByType($type)
    {
        return $this->repository->getActiveByType($type);
    }

    public function create(array $data)
    {
        $template = $this->repository->create($data);
        return $template;
    }

    public function update($id, array $data)
    {
        $template = $this->repository->update($id, $data);
        return $template;
    }

    public function delete($id)
    {
        $result = $this->repository->delete($id);
        return $result;
    }

    public function paginate($perPage = 15)
    {
        return $this->repository->paginate($perPage);
    }

    public function clearCache()
    {
        // No cache to clear with Cachable
        return true;
    }
}