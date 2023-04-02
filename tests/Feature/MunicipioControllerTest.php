<?php

namespace Tests\Feature;

use Generator;
use Illuminate\Http\Response;
use Illuminate\Testing\Fluent\AssertableJson;
use PHPUnit\Framework\Attributes\DataProvider;
use Tests\TestCase;

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
                $assert->hasAll(['name', 'ibge_code'])
            )
        );
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
