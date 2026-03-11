<?php

namespace App\Providers;

use App\Repositories\Contracts\ComplianceRepositoryInterface;
use App\Repositories\Contracts\CustomerRepositoryInterface;
use App\Repositories\Contracts\FxQuoteRepositoryInterface;
use App\Repositories\Contracts\RecipientRepositoryInterface;
use App\Repositories\Contracts\TransactionRepositoryInterface;
use App\Repositories\Eloquent\ComplianceRepository;
use App\Repositories\Eloquent\CustomerRepository;
use App\Repositories\Eloquent\FxQuoteRepository;
use App\Repositories\Eloquent\RecipientRepository;
use App\Repositories\Eloquent\TransactionRepository;
use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(CustomerRepositoryInterface::class, CustomerRepository::class);
        $this->app->bind(RecipientRepositoryInterface::class, RecipientRepository::class);
        $this->app->bind(TransactionRepositoryInterface::class, TransactionRepository::class);
        $this->app->bind(FxQuoteRepositoryInterface::class, FxQuoteRepository::class);
        $this->app->bind(ComplianceRepositoryInterface::class, ComplianceRepository::class);
    }
}
