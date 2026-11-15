<?php

namespace Models;
class Pagination
{
    private int $currentPage, $perPage, $totalItems;

    public function __construct(int $currentPage = 1, int $perPage = 10, int $totalItems = 0)
    {
        $this->currentPage = $currentPage;
        $this->perPage = $perPage;
        $this->totalItems = $totalItems;
    }

    public function getOffset(): int
    {
        return ($this->currentPage - 1) * $this->perPage;
    }

    public function getLimit(): int
    {
        return $this->perPage;
    }

    public function getTotalPages(): int
    {
        return ceil($this->totalItems / $this->perPage);
    }

    public function getCurrentPage(): int
    {
        return $this->currentPage;
    }

    public function getPerPage(): int
    {
        return $this->perPage;
    }

    public function getTotalItems(): int
    {
        return $this->totalItems;
    }

    public function hasPrev(): bool
    {
        return $this->currentPage > 1;
    }

    public function hasNext(): bool
    {
        return $this->currentPage < self::getTotalPages();
    }

    public function getPrevPage(): int
    {
        return max(1, $this->currentPage - 1);
    }

    public function getNextPage(): int
    {
        return min(self::getTotalPages(), $this->currentPage + 1);
    }
}