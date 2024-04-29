<?php
declare(strict_types=1);

interface CrudEntityInterface
{
    /**
     * @param array<int,array<string, mixed>> $query_params
     */
    public function get(array $query_params): Response;
    /**
     * @param array<string,mixed> $data
     */
    public function put(array $data): Response;
    public function post(): Response;
    public function delete(): Response;
}
