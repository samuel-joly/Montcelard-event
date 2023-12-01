<?php
declare(strict_types=1);

interface CrudEntityInterface
{
    public function get(int $id = null): Response;
    public function post(array $data): Response;
    public function put(array $data, int $id): Response;
    public function delete(int $id): Response;
}
