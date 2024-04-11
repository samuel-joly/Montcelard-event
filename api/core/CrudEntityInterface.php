<?php
declare(strict_types=1);

interface CrudEntityInterface
{
    /**
     * @param array<string,mixed> $query_params
     */
    public function get(array $query_params): Response;
    /**
     * @param array<string,mixed> $data
     */
    public function post(array $data): Response;
    /**
     * @param array<string,mixed> $data
     */
    public function put(array $data, int $id): Response;
    /**
     * @param int $id
     */
    public function delete(int $id): Response;
}
