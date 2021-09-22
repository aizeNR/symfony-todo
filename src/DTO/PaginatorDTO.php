<?php

namespace App\DTO;

class PaginatorDTO
{
    private int $page;
    private int $limit;

    /**
     * @param int $page
     * @param int $limit
     */
    public function __construct(int $page = 1, int $limit = 10)
    {
        $this->page = $page;
        $this->limit = $limit;
    }

    /**
     * @return int
     */
    public function getPage(): int
    {
        return $this->page;
    }

    /**
     * @return int
     */
    public function getLimit(): int
    {
        return $this->limit;
    }
}