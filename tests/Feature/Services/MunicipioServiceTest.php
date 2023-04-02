<?php

namespace Tests\Feature\Services;

use App\Services\MunicipioService;
use App\Exceptions\ProvedorIndisponivelException;
use Generator;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Http;
use PHPUnit\Framework\Attributes\DataProvider;
use Tests\TestCase;

class MunicipioServiceTest extends TestCase
{
    private MunicipioService $service;

    public function setUp(): void
    {
        parent::setUp();

        $this->service = App::make(MunicipioService::class);
    }

    #[DataProvider('provedorDadosProvider')]
    public function test_busca_dados_retorna_sucesso(
        string $fixture,
        string $provedorUrl
    ): void {
        $data = file_get_contents($fixture);

        Http::fake(
            [
                $provedorUrl => Http::response($data),
            ]
        );

        $atual = $this->service->buscarMunicipioUf('pb');

        $this->assertArrayHasKey('ibge_code', current($atual));
        $this->assertArrayHasKey('name', current($atual));
        $this->assertArrayNotHasKey('microrregiao', current($atual));
    }

    public function test_provider_incomunicavel_ou_inexistente(): void
    {
        $this->expectException(ProvedorIndisponivelException::class);
        $this->expectExceptionMessage("Provedor dos dados indisponível no momento");

        $this->service->buscarMunicipioUf('foo');
    }

    public static function provedorDadosProvider(): Generator
    {
        yield 'Caso em que Brasil api é o provedor.' => [
            sprintf('%s/../../Fixtures/municipios_pb.json', __DIR__),
            'https://brasilapi.com.br/*'
        ];

        yield 'Caso em que ibge é o provedor.' => [
            sprintf('%s/../../Fixtures/municipios_pb_ibge.json', __DIR__),
            'https://*.ibge.gov.br/*'
        ];
    }
}
