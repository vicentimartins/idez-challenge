<?php

namespace Tests\Feature;

use App\Services\MunicipioService;
use Generator;
use Illuminate\Support\Facades\App;
use Illuminate\Testing\AssertableJsonString;
use Illuminate\Testing\Fluent\AssertableJson;
use Tests\TestCase;
use PHPUnit\Framework\Attributes\DataProvider;

class MunicipioControllerTest extends TestCase
{
    #[DataProvider('ufProvider')]
    public function test_lista_municipios_validos(string $uf, int $codigoHttp): void
    {
        $response = $this->postJson(route('municipio.buscar'), ['uf' => $uf]);

        $this->assertSame($codigoHttp, $response->getStatusCode());
    }

    public function test_lista_municipios_possui_campos_validos(): void
    {
        $response = $this->postJson(route('municipio.buscar'), ['uf' => 'PB']);
        $response->assertJson(
            fn (AssertableJson $assert) =>
            $assert->first(
                fn (AssertableJson $assert) =>
                $assert->hasAll(['nome', 'codigo_ibge'])
            )
        );
    }

    public static function ufProvider(): Generator
    {
        yield [
            'uf' => 'pb',
            'codigoHttp' => 200,
        ];

        yield [
            'uf' => 'RS',
            'codigoHttp' => 200,
        ];

        yield [
            'uf' => 'foo',
            'codigoHttp' => 422,
        ];
    }
}
