<?php

namespace App\Repositories\Eloquent;

use App\Models\FxQuote;
use App\Repositories\Contracts\FxQuoteRepositoryInterface;

class FxQuoteRepository implements FxQuoteRepositoryInterface
{
    public function create(array $data): FxQuote
    {
        return FxQuote::create($data);
    }

    public function findById(int $id): ?FxQuote
    {
        return FxQuote::find($id);
    }

    public function all(array $filters = [])
    {
        $query = FxQuote::with('agent')->latest();
        return $query->paginate(20);
    }

    public function recent(int $limit = 20)
    {
        return FxQuote::with('agent')->latest()->limit($limit)->get();
    }
}
