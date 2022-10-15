<p align="center">
  <img src="./public/images/logo-dark.svg" alt="Logo do Trellásio" width="350" />
</p>

<div align="center">
  <p align="center">Uma ferramenta para ajudar na gestão de tarefas de múltiplos times utilizando o Scrum</p>
</div>

### Configurando o Trelássio

<small>⚠️ ATENÇÃO ⚠️ Você deverá ter o [docker](https://docs.docker.com/engine/install/) e [docker-compose](https://docs.docker.com/compose/install/) instalado em sua máquina.</small>

- Subindo o container docker:

  ```bash
  $ docker-compose up -d
  ```

- Crie o arquivo de váriaveis de ambiente copiando e renomando o `.env.example` para `.env`:

  ```bash
  $ cp .env.example .env
  ```

- Instale as dependências da aplicação:

  ```bash
  $ docker-compose exec board-server composer installl
  $ docker-compose exec board-server npm ci
  ```

- Crie a chave de criptografia da aplicação:

  ```bash
  $ docker-compose exec board-server php artisan key:generate
  ```

- Dê as permissões corretas para as pastas de `cache` e `storage`:

  ```bash
  $ docker-compose exec board-server chown -R www-data:www-data storage/
  $ docker-compose exec board-server chown -R www-data:www-data bootstrap/cache/
  ```

- Para o funcionamento correto, você precisa preencher a base de dados com alguns registros. Basta executar:
  ```
  $ docker-compose exec board-server php artisan setup:app
  ```
<small> **detalhe**: Caso você esteja em ambiente de desenvolvimento e não possua nenhum outro usuário na base, o comando acima cria um usuário com as seguintes credenciais email: `admin@admin.com` e senha `admin`


- Realize o *build* do *frontend*:

  ```bash
  $ docker-compose exec board-server npm run watch
  ```
  ou 
  ```bash
  $ docker-compose exec board-server npm run dev
  ```
  ou para produção
  ```bash
  $ docker-compose exec board-server npm run prod
  ```

<small> **nota**: `docker-composer exec board-server` pode ser substituido por `./on-server.sh`
