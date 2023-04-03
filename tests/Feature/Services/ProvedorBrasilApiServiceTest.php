<?php

namespace Tests\Feature\Services;

use App\Services\ProvedorBrasilApiService;
use Generator;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\App;
use Mockery\MockInterface;
use PHPUnit\Framework\Attributes\DataProvider;
use Tests\TestCase;

class ProvedorBrasilApiServiceTest extends TestCase
{
    private ProvedorBrasilApiService $service;

    public function setUp(): void
    {
        parent::setUp();

        $this->service = App::make(ProvedorBrasilApiService::class);
    }

    #[DataProvider('validaRequisicaoProvider')]
    public function test_valida_falha_requisicao_para_ibge(bool $esperado): void
    {
        $responseMock = $this->mock(Response::class, function (MockInterface $mock) use ($esperado) {
            $mock->shouldReceive('failed')
                ->andReturn($esperado);
        });

        $this->assertEquals($esperado, $this->service->requisicaoFalhou($responseMock));
    }

    public function test_dado_retornado_por_ibge_eh_transformado_corretamente(): void
    {
        $dados = file_get_contents(
            sprintf('%s/../../Fixtures/municipios_pb.json', __DIR__)
        );

        $atual = $this->service->processarRetorno($dados);

        $this->assertArrayHasKey('ibge_code', current($atual));
        $this->assertArrayHasKey('name', current($atual));
        $this->assertArrayNotHasKey('mesorregiao', current($atual));
    }

    public static function validaRequisicaoProvider(): Generator
    {
        yield 'Caso em que a validação retorna verdadeiro.' => [
            'esperado' => true,
        ];

        yield 'Caso em que a validação falha.' => [
            'esperado' => false,
        ];
    }
}
