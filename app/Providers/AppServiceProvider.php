<?php

namespace App\Providers;

use App\Interfaces\AddressInterface\AddressRepositoryInterface;
use App\Interfaces\BookInterface\BookRepositoryInterface;
use App\Interfaces\UserInterfaces\UserRepositoryInterfaces;
use App\Repository\AddressRepository\AddressRepository;
use App\Repository\BookRepository\BookRepository;
use App\Repository\UserRepository\UserRepository;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(UserRepositoryInterfaces::class, UserRepository::class);
        $this->app->bind(BookRepositoryInterface::class, BookRepository::class);
        $this->app->bind(AddressRepositoryInterface::class, AddressRepository::class);
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
