# README

Projeto de encurtador de urls.


## Backend

Para executar as aplicação de backend:

Entrar no diretório da aplicação de backend:

```sh
cd backend
```

Copiar o arquivo de ambiente padrão:

```sh
cp .env-example .env
```

Instalar as dependências:

```sh
docker run --rm \
    -u "$(id -u):$(id -g)" \
    -v $(pwd):/opt \
    -w /opt \
    laravelsail/php80-composer:latest \
    composer install --ignore-platform-reqs
```

Gerar a chave da aplicação:

```sh
./vendor/bin/sail artisan key:generate
```

Executar os containeres:

```sh
./vendor/bin/sail up -d
```

Executar migrations com as seeds:

```sh
./vendor/bin/sail artisan migrate --seed
```

Para executar os testes:

```sh
./vendor/bin/sail artisan test # pelo artisan
./vendor/bin/sail exec laravel.test ./vendor/bin/phpunit # direto pelo phpunit
```

# Frontend

Para executar as aplicação de frontend:

Buildar a imagem do frontend:
```sh
docker build . -t frontend
```

Executar o container a imagem do frontend:
```sh
docker run -it -p 3000:3000 frontend:latest
```
