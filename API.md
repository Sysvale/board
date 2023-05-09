## Como usar a api do trelássio

0. Pré-configurações
1. Criar um client
2. gerar um token
3. enviar o token no cabeçalho da requisição
4. Rotas disponíveis

### 0. Pré-configurações
Você deve ter executado o comando `php artisan passport:install` para ter gerado as chaves que o passport usa. Para saber se esse comando já foi executado antes, você pode verificar se os arquivos `storage/oauth-private.key` e `storage/oauth-public.key` existem.

### 1. Criar um Client
#### Obtenha o company_id
Encontre seu usuário pelo tinker 

`$user = User::whereEmail('seu-email-aqui')->first()`

e acesse a Company através de member: 

`$company_id = $user->member->company->id`

#### Crie ou acesse o client
Rode o comando `php artisan passport:company <company_id>` com o company_id obtido no passo anterior.

⚠️ ATENÇÃO ⚠️  A saída desse comando é um `client ID` e um `client Secret`, são informações sensíveis e devem ser mantidas em segredo, adequadamente, como tal, 

⚠️ ATENÇÃO ⚠️  Embora esse comando gere um client do tipo `client credentials`, o comando `passport:client --client` não funcionará, você terá um client, e até conseguirá um token, mas como a companhia não será vinculada ao token, as requisições feitas com esse token não terão efeito.

### 2. Gerar um token
Faça uma requisição POST à rota `/oauth/token` com o payload:
``` json
{
	"grant_type": "client_credentials",
	"client_id": "{{client_id}}",
	"client_secret": "{{client_secret}}",
	"scope": ""
}
```
O resultado dessa requisição deve ser parecido com:
``` json
{
	"token_type": "Bearer",
	"expires_in": 31622400,
	"access_token": "<access-token-aqui>"
}
```

### 3. Enviar o token no cabeçalho da requisição
Para fazer a autenticação com o token em suas requisições, adicione o header:
```json
"Authorization": "Bearer <access-token-aqui>"
```

Opcionalmente, envie o header
``` json
"Accept": "application/json"
```
para evitar redirecionamentos em caso de erros de validação ou autenticação.

### 4. Rotas disponíveis

|	Rota			|	Método	|	Descrição						|
|	---				|	---		|	---								|
|	/oauth/token	|	POST	|	Gera um token para um client	|
|	/api/cards		|	POST	|	Cria um card em uma lista		|

#### /oauth/token
##### Request [POST]
```json
{
	"grant_type": "client_credentials", // use sempre este tipo
	"client_id": "{{client_id}}", // o clientid gerado para a company
	"client_secret": "{{client_secret}}", // o client secret gerado para a company
	"scope": "" // atualmente não usamos scopes, preencher este campo não tem efeito
}
```

##### Response: [200 - OK]
``` json
{
	"token_type": "Bearer", // sempre retorna este valor
	"expires_in": 31622400, // milisegundos até a expiração do token
	"access_token": "<access-token-aqui>" // o token propriamente dito
}
```

#### /api/cards
##### Request [POST]
```json
{
	"title": "Card criado via api", // titulo do card
	"type":  "user-story", // tipo do card. para mais tipos ver: App/Constants/CardTypes
	"board_list_id" : "645259f8ec05ef07130f31a8" // id da lista onde o card será criado
}
```

##### Response: [201 - CREATED]
``` json
{
	"id": "6458e1a8a90d9a6e0e697d02", // id do card criado
	"number": 11, // numero do card
	"created_at": "08\/05\/2023 11:48", // data de criação
	"board_list_id": "645259f8ec05ef07130f31a8", // id da lista onde o card foi criado
	"title": "Card criado via api", // titulo do card
	"first_default_board_list_id": null
}
```

##### Response: [401 - UNAUTHORIZED] indica erro de autenticação
``` json
{
	"message": "Unauthenticated."
}
```

##### Response: [422 - UNPROCESSABLE] indica erros de validação
``` json
{
	"message": "The given data was invalid.",
	"errors": {
		// cada campo com erro tem uma chave em errors, com um array de erros de validação para o campo
		"type": [
			"The selected type is invalid."
		]
	}
}
```
