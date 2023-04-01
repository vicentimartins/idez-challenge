<?php

namespace Tests\Feature;

use Generator;
use Tests\TestCase;
use PHPUnit\Framework\Attributes\DataProvider;

class MunicipioControllerTest extends TestCase
{
    #[DataProvider('ufProvider')]
    public function test_lista_municipios_validos(string $uf, int $codigoHttp): void
    {
        $response = $this->post(route('municipio.buscar'), ['uf' => $uf]);

        $this->assertSame($codigoHttp, $response->getStatusCode());
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
