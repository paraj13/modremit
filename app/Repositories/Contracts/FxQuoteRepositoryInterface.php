<?php

namespace App\Repositories\Contracts;

interface FxQuoteRepositoryInterface
{
    public function create(array $data): \App\Models\FxQuote;
    public function findById(int $id): ?\App\Models\FxQuote;
    public function recent(int $limit = 20);
}
