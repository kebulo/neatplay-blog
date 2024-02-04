<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Contracts\BlogsContract;
use App\Repositories\BlogsRepository;

use App\Contracts\CategoryContract;
use App\Repositories\CategoryRepository;

use App\Contracts\CommentContract;
use App\Repositories\CommentRepository;

class RepositoryServiceProvider extends ServiceProvider
{
    protected $repositories = [
        BlogsContract::class    => BlogsRepository::class,
        CategoryContract::class => CategoryRepository::class,
        CommentContract::class => CommentRepository::class,
    ];

    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        foreach ($this->repositories as $interface => $implementation) {
            $this->app->bind($interface, $implementation);
        }
    }
}