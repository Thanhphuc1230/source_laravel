<?php

namespace App\Repositories;

interface MailTemplateRepositoryInterface
{
    public function getAll();

    public function getById($id);

    public function getByType($type);

    public function getActiveByType($type);

    public function create(array $data);

    public function update($id, array $data);

    public function delete($id);

    public function paginate($perPage = 15);
}