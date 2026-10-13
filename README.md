# API REST de Usuários e Autenticação

Projeto desenvolvido para a UC **Desenvolver Serviços Web**, do curso Técnico em Informática.

## Identificação

- **Aluno:** Rafael De Oliveira De Almeida
- **Curso:** Técnico em Informática
- **Unidade Curricular:** Desenvolver Serviços Web
- **Linguagem:** PHP
- **Framework:** Slim Framework 4

## Sobre o projeto

Esta API REST permite realizar operações de cadastro, consulta, atualização e exclusão de usuários, além de autenticação por login e alteração de senha.

Os dados são enviados e recebidos no formato JSON, utilizando métodos HTTP e códigos de status apropriados para cada operação.

## Tecnologias utilizadas

- PHP 8.2
- Slim Framework 4
- Slim PSR-7
- Composer
- Postman para testar os endpoints

## Instalação

### 1. Instalar as dependências

Com o Composer instalado, abra o terminal na pasta do projeto e execute:

```bash
composer install
```

### 2. Iniciar o servidor

Execute o seguinte comando no terminal:

```bash
php -S 127.0.0.1:8081 -t public public/index.php
```

A API estará disponível em:

`http://127.0.0.1:8081`

## Endpoints disponíveis

| Método | Rota | Descrição |
|---|---|---|
| GET | `/status` | Verifica o funcionamento da API |
| GET | `/usuarios` | Lista todos os usuários |
| GET | `/usuarios/{id}` | Consulta um usuário pelo ID |
| POST | `/usuarios` | Cadastra um novo usuário |
| PUT | `/usuarios/{id}` | Atualiza os dados de um usuário |
| DELETE | `/usuarios/{id}` | Exclui um usuário |
| POST | `/login` | Realiza a autenticação |
| PUT | `/usuarios/{id}/senha` | Altera a senha de um usuário |

## Decisões técnicas

- Foi utilizado o Slim Framework 4 para criação e gerenciamento das rotas.
- Os dados são enviados e recebidos no formato JSON.
- O método `getParsedBody()` é utilizado para acessar os dados enviados nas requisições.
- Os usuários são armazenados em um array durante a execução da aplicação.
- Os IDs dos novos usuários são gerados automaticamente.
- A API utiliza códigos HTTP, como 200, 201, 204, 400, 401, 404 e 409, conforme o resultado das operações.
- A senha não é incluída nas respostas de consulta ou cadastro de usuários.
- Os dados são temporários e voltam ao estado inicial quando o servidor é reiniciado.

## Testes

Os endpoints foram testados utilizando o Postman, verificando as respostas JSON e os códigos de status HTTP.

### Evidências dos testes

Adicione abaixo as capturas de tela dos testes realizados no Postman:

- GET `/status`
- GET `/usuarios`
- GET `/usuarios/{id}`
- POST `/usuarios`
- PUT `/usuarios/{id}`
- DELETE `/usuarios/{id}`
- POST `/login`
- PUT `/usuarios/{id}/senha`

## Considerações finais

O desenvolvimento deste projeto permitiu praticar a criação de uma API REST com PHP e Slim Framework, o gerenciamento de rotas, o tratamento de requisições e respostas JSON, a utilização de códigos de status HTTP e a implementação de operações básicas de autenticação de usuários.