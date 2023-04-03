<?php

namespace Tests\Feature\Services;

use App\Services\ProvedorIbgeService;
use Generator;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\App;
use Mockery\MockInterface;
use PHPUnit\Framework\Attributes\DataProvider;
use Tests\TestCase;

class ProvedorIbgeServiceTest extends TestCase
{
    private ProvedorIbgeService $service;

    public function setUp(): void
    {
        parent::setUp();

        $this->service = App::make(ProvedorIbgeService::class);
    }

    #[DataProvider('validaRequisicaoProvider')]
    public function test_valida_falha_requisicao_para_ibge(string $retorno, bool $esperado): void
    {
        $responseMock = $this->mock(Response::class, function (MockInterface $mock) use ($retorno) {
            $mock->shouldReceive('body')
                ->andReturn($retorno);
        });

        $this->assertEquals($esperado, $this->service->requisicaoFalhou($responseMock));
    }

    public function test_dado_retornado_por_ibge_eh_transformado_corretamente(): void
    {
        $dados = file_get_contents(
            sprintf('%s/../../Fixtures/municipios_pb_ibge.json', __DIR__)
        );

        $atual = $this->service->processarRetorno($dados);

        $this->assertArrayHasKey('ibge_code', current($atual));
        $this->assertArrayHasKey('name', current($atual));
        $this->assertArrayNotHasKey('mesorregiao', current($atual));
    }

    public static function validaRequisicaoProvider(): Generator
    {
        yield 'Caso em que a validação retorna verdadeiro.' => [
            'retorno' => '[]',
            'esperado' => true,
        ];

        yield 'Caso em que a validação falha.' => [
            'retorno' => '[{"foo": "bar"}]',
            'esperado' => false,
        ];
    }
}
