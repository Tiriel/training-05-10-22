<?php

namespace App;

use App\Repository\MovieRepository;
use Symfony\Component\DependencyInjection\Attribute\Autoconfigure;

#[Autoconfigure(tags: ['app.my_service'])]
class MyService
{
    public function __construct(
        private readonly MovieRepository $repository,
        private readonly string $databaseUrl
    ) {}
}