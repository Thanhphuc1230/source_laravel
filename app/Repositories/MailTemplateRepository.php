<?php

namespace App\Repositories;

use App\Models\MailTemplate;

class MailTemplateRepository implements MailTemplateRepositoryInterface
{
    protected $model;

    public function __construct(MailTemplate $model)
    {
        $this->model = $model;
    }

    public function getAll()
    {
        return $this->model->all();
    }

    public function getById($id)
    {
        return $this->model->find($id);
    }

    public function getByType($type)
    {
        return $this->model->where('type', $type)->get();
    }

    public function getActiveByType($type)
    {
        return $this->model->where('type', $type)->where('is_active', true)->first();
    }

    public function create(array $data)
    {
        return $this->model->create($data);
    }

    public function update($id, array $data)
    {
        $template = $this->getById($id);
        if ($template) {
            $template->update($data);
            return $template;
        }
        return null;
    }

    public function delete($id)
    {
        $template = $this->getById($id);
        if ($template) {
            return $template->delete();
        }
        return false;
    }

    public function paginate($perPage = 15)
    {
        return $this->model->paginate($perPage);
    }
}