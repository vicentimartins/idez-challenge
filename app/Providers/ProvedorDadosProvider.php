<?php

namespace App\Providers;

use App\Contracts\IProvedor;
use App\Services\ProvedorBrasilApiService;
use App\Services\ProvedorIbgeService;
use Illuminate\Support\ServiceProvider;

class ProvedorDadosProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(
            IProvedor::class,
            function () {
                if ('ibge' === env('PROVEDOR_DADOS')) {
                    return new ProvedorIbgeService();
                }

                return new ProvedorBrasilApiService();
            }
        );
    }
}
