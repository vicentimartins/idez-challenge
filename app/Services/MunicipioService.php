<?php

namespace App\Services;

use App\Contracts\IProvedor;
use App\Exceptions\ProvedorIndisponivelException;
use App\Models\Municipio;
use App\Models\Uf;
use Generator;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Http;

class MunicipioService
{
    public function __construct(private IProvedor $provedorDadosService)
    {
    }

    public function buscarMunicipioUf(string $uf): void
    {
        $response = Http::get(
            sprintf($this->definirProvedor(), strtoupper($uf))
        );

        if ($this->provedorDadosService->requisicaoFalhou($response)) {
            throw new ProvedorIndisponivelException(
                'Provedor dos dados indisponível no momento',
                Response::HTTP_SERVICE_UNAVAILABLE
            );
        }

        $this->relacionarMunicipioUf(
            $this->provedorDadosService->processarRetorno($response),
            strtoupper($uf)
        );
    }

    private function relacionarMunicipioUf(array $municipios, $uf): void
    {
        $uf = Uf::whereName($uf)->first() ?? Uf::create(['name' => $uf]);
        $municipioModel = new Municipio();

        foreach ($municipios as $municipio) {
            if (!$municipioDb = $municipioModel->find($municipio['ibge_code'])) {
                $municipioDb = $municipioModel->create(
                    [
                        'name' => $municipio['name'],
                        'ibge_code' => $municipio['ibge_code'],

                    ]
                );
            }

            $uf->municipios()
                ->attach($municipioDb->id);
        }
    }

    private function definirProvedor(): string
    {
        if ('ibge' === env('PROVEDOR_DADOS')) {
            return config('apiprovider.ibge');
        }

        return config('apiprovider.brasil');
    }
}
