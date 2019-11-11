# Desafio devninjas

Para a contrução da API Rest foi utilizado: 

- **Symfony 4.3**.
- **PHP 7.1.32**.
- **MySql 8.0.0**. 
- **PhpStorm 2019.1**.
- **Postman**.

## Passos para rodar o projeto

1.  Configurar a database url no arquivo **.env** que se encontra na raiz do projeto:
	- `DATABASE_URL=mysql://user:password@127.0.0.1:3306/db_devninjas. ` 
2. Criar o banco configurado no .env:
	- `php bin/console doctrine:database:create`
  
3. Criar as tabelas do banco executando a migration:
	- `php bin/console doctrine:migrations:migrate`

4. Popular o banco executando o script **db_devninjas.sql** (se encontra na raiz do projeto):
	- `php bin/console doctrine:database:import db_devninjas.sql`

4. Subir a aplicação no localhost:
	- `symfony serve --port=8001`

### Executar métodos via Postman

- Obs.: Para o **POST** utiliza-se o formulário do **Postman** (aba Body, radio button x-www-form-urlencoded). 

#### POST `http://127.0.0.1:8001/products/`

| Campo       | Valor     |
|-------------|----------|
| sku          |  9552572451438042    |
| name        | Blusa manga longa   |
| price         | 91.90   |

#### GET `http://127.0.0.1:8001/products/`

- Retorna todos os produtos cadastrados.

### POST `http://127.0.0.1:8001/customers/`

| Campo       | Valor     |
|-------------|----------|
| name       | Maria Souza   |
| cpf          |  21749897008    |
| email         | m.souza@email.com    |

### POST `http://127.0.0.1:8001/orders/`

| Campo       | Valor     |
|-------------|----------|
| customerId       | 1   |
| total         |  159.90    |
| status         | ACTIVATED   |
| productId         | 3   |
| amount         | 1   |
| price_unit         | 159.90   |
| total         | 159.90   |

### PUT `http://127.0.0.1:8001/orders/1`

- Atualiza o status para "CANCELED" o pedido de venda informado.

### GET `http://127.0.0.1:8001/orders/`

- Retorna todos os pedidos de venda cadastrados.
