<?php

namespace Tests\Feature\Services;

use App\Services\MunicipioService;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;
use App\Exceptions\ProvedorIndisponivelException;

class MunicipioServiceTest extends TestCase
{
    private MunicipioService $service;

    public function setUp(): void
    {
        parent::setUp();

        $this->service = App::make(MunicipioService::class);
    }

    public function test_provider_retorna_sucesso(): void
    {
        $uf = 'pb';
        $data = json_decode(
            file_get_contents(
                sprintf('%s/../../Fixtures/municipios_pb.json', __DIR__)
            ),
            true
        );

        $response = response()->json($data);

        Http::fake(
            [
                sprintf('https://brasilapi.com.br/api/ibge/municipios/v1/%s', $uf) => $response
            ]
        );

        $atual = $this->service->buscarMunicipioDaUf($uf);

        $this->assertSame($data, $atual);
    }

    public function test_provider_incomunicavel_ou_inexistente(): void
    {
        $this->expectException(ProvedorIndisponivelException::class);
        $this->expectExceptionMessage("Provedor dos dados indisponível no momento");

        $this->service->buscarMunicipioDaUf('foo');
    }
}
