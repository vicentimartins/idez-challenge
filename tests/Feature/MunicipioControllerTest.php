<?php

namespace Tests\Feature;

use Generator;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Redis;
use PHPUnit\Framework\Attributes\DataProvider;
use Tests\TestCase;

class MunicipioControllerTest extends TestCase
{
    use RefreshDatabase;

    public function setUp(): void
    {
        parent::setUp();

        $data = file_get_contents(sprintf('%s/tests/Fixtures/municipios_pb_ibge.json', base_path()));

        Http::fake(
            [
                'https://*.ibge.gov.br/*' => Http::response($data),

            ]
        );

        Redis::shouldReceive('get')
            ->withAnyArgs()
            ->andReturn([]);

        Redis::shouldReceive('set')
            ->withAnyArgs();
    }

    #[DataProvider('ufProvider')]
    public function test_lista_municipios_validos(string $uf, int $codigoHttp): void
    {
        $response = $this->postJson(route('municipio.buscar', ['page' => 1], false), ['uf' => $uf]);

        $this->assertSame($codigoHttp, $response->getStatusCode());
    }

    public function test_lista_municipios_possui_campos_validos(): void
    {
        $response = $this->postJson(route('municipio.buscar', ['page' => 1], false), ['uf' => 'PB']);

        $response->assertJson(json_decode($response->getContent(), true));
    }

    public static function ufProvider(): Generator
    {
        yield [
            'uf' => 'pb',
            'codigoHttp' => Response::HTTP_OK,
        ];

        yield [
            'uf' => 'RS',
            'codigoHttp' => Response::HTTP_OK,
        ];

        yield [
            'uf' => 'foo',
            'codigoHttp' => Response::HTTP_UNPROCESSABLE_ENTITY,
        ];
    }
}
