# Idez dev challenge #

Este é um projeto desafio que visa avaliar a habilidade do desenvolvedor em
relação a implementação de código implementada pelo mesmo.

## Requisitos ##

* Criar uma rota para pesquisar e listar os municípios de uma UF.
* Resposta da requisição deve conter, uma lista de municípios com os seguintes campos:
  * **name**: Nome do município.
  * **ibge_code**: Código IBGE desse município.
* Deve ser utilizado como providers as seguintes APIs:
  * **Brasil API**: https://brasilapi.com.br/api/ibge/municipios/v1/RS
  * **IBGE**: https://servicodados.ibge.gov.br/api/v1/localidades/estados/rs/municipios
* O provider usado deve ser definido via variável de ambiente.
* Deve conter testes unitários e de integração.

### Bônus ###
* Uso de Cache.
* Tratamento de exceções.
* Documentação do projeto (pensando na possibilidade do projeto crescer e possuir outros endpoints * futuramente).
* Github Actions.
* Commits atômicos e descritivos.
* Paginação dos resultados.
* Criação de SPA consumindo o endpoint criado.
* Disponibilização do projeto em algum ambiente cloud.
* Conteinerização.

## Sobre o projeto ##

Este projeto foi implementado usando o framework Laravel.

O mesmo roda sobre containers usando docker.

> É indispensável possuir as ferramentas [docker compose](https://docs.docker.com/compose/install/) e
> [composer](https://getcomposer.org/doc/00-intro.md#installation-linux-unix-macos) instaladas em sua máquina.

### Dev environment ###

Crie um clone deste repositório em seu ambiente local

```bash
git clone git@github.com:vicentimartins/idez-challenge.git
```

Instale as dependências do projeto usando o composer a partir do diretório **idez-challenge**

```bash
composer install -o
```

Levante os containers

```bash
vendor/bin/sail up -d
```

Pronto! Seu projeto está disponível para alterações.
