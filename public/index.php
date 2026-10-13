
<?php

require __DIR__ . '/../vendor/autoload.php';

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Factory\AppFactory;

$app = AppFactory::create();
$app->addBodyParsingMiddleware();

$usuarios = [
    ['id' => 1, 'nome' => 'João Silva', 'email' => 'joao@email.com', 'login' => 'joao', 'senha' => '1234'],
    ['id' => 2, 'nome' => 'Maria Souza', 'email' => 'maria@email.com', 'login' => 'maria', 'senha' => '1234'],
    ['id' => 3, 'nome' => 'Pedro Santos', 'email' => 'pedro@email.com', 'login' => 'pedro', 'senha' => '1234'],
    ['id' => 4, 'nome' => 'Ana Oliveira', 'email' => 'ana@email.com', 'login' => 'ana', 'senha' => '1234'],
    ['id' => 5, 'nome' => 'Carlos Lima', 'email' => 'carlos@email.com', 'login' => 'carlos', 'senha' => '1234']
];

function resposta(Response $response, array $dados, int $status = 200): Response
{
    $json = json_encode($dados, JSON_UNESCAPED_UNICODE);

    $response->getBody()->write($json);

    return $response
        ->withHeader('Content-Type', 'application/json; charset=utf-8')
        ->withStatus($status);
}

// GET /status
$app->get('/status', function (Request $request, Response $response) {
    return resposta($response, ['status' => 'ok']);
});

// GET /usuarios
$app->get('/usuarios', function (Request $request, Response $response) use (&$usuarios) {
    $lista = [];

    foreach ($usuarios as $usuario) {
        unset($usuario['senha']);
        $lista[] = $usuario;
    }

    return resposta($response, $lista);
});

// GET /usuarios/{id}
$app->get('/usuarios/{id}', function (Request $request, Response $response, array $args) use (&$usuarios) {
    foreach ($usuarios as $usuario) {
        if ($usuario['id'] == (int) $args['id']) {
            unset($usuario['senha']);
            return resposta($response, $usuario);
        }
    }

    return resposta($response, ['erro' => 'Usuário não encontrado'], 404);
});

// POST /usuarios
$app->post('/usuarios', function (Request $request, Response $response) use (&$usuarios) {
    $dados = $request->getParsedBody();

    if (!is_array($dados)) {
        return resposta($response, ['erro' => 'Envie os dados em JSON'], 400);
    }

    foreach (['nome', 'email', 'login', 'senha'] as $campo) {
        if (!isset($dados[$campo]) || trim((string) $dados[$campo]) === '') {
            return resposta($response, ['erro' => "O campo $campo é obrigatório"], 400);
        }
    }

    foreach ($usuarios as $usuario) {
        if ($usuario['login'] === $dados['login']) {
            return resposta($response, ['erro' => 'Login já cadastrado'], 409);
        }
    }

    $ids = array_column($usuarios, 'id');
    $novoId = max($ids) + 1;

    $novoUsuario = [
        'id' => $novoId,
        'nome' => $dados['nome'],
        'email' => $dados['email'],
        'login' => $dados['login'],
        'senha' => $dados['senha']
    ];

    $usuarios[] = $novoUsuario;

    unset($novoUsuario['senha']);

    return resposta($response, $novoUsuario, 201);
});

// PUT /usuarios/{id}
$app->put('/usuarios/{id}', function (Request $request, Response $response, array $args) use (&$usuarios) {
    $dados = $request->getParsedBody();

    if (!is_array($dados)) {
        return resposta($response, ['erro' => 'Envie os dados em JSON'], 400);
    }

    foreach ($usuarios as &$usuario) {
        if ($usuario['id'] == (int) $args['id']) {
            foreach (['nome', 'email', 'login'] as $campo) {
                if (isset($dados[$campo]) && trim((string) $dados[$campo]) !== '') {
                    $usuario[$campo] = $dados[$campo];
                }
            }

            $retorno = $usuario;
            unset($retorno['senha']);

            return resposta($response, $retorno);
        }
    }

    return resposta($response, ['erro' => 'Usuário não encontrado'], 404);
});

// DELETE /usuarios/{id}
$app->delete('/usuarios/{id}', function (Request $request, Response $response, array $args) use (&$usuarios) {
    foreach ($usuarios as $indice => $usuario) {
        if ($usuario['id'] == (int) $args['id']) {
            array_splice($usuarios, $indice, 1);

            return $response->withStatus(204);
        }
    }

    return resposta($response, ['erro' => 'Usuário não encontrado'], 404);
});

// POST /login
$app->post('/login', function (Request $request, Response $response) use (&$usuarios) {
    $dados = $request->getParsedBody();

    if (
        !is_array($dados) ||
        !isset($dados['login'], $dados['senha']) ||
        $dados['login'] === '' ||
        $dados['senha'] === ''
    ) {
        return resposta($response, ['erro' => 'Informe login e senha'], 400);
    }

    foreach ($usuarios as $usuario) {
        if (
            $usuario['login'] === $dados['login'] &&
            $usuario['senha'] === $dados['senha']
        ) {
            unset($usuario['senha']);

            return resposta($response, [
                'mensagem' => 'Login realizado com sucesso',
                'usuario' => $usuario
            ]);
        }
    }

    return resposta($response, ['erro' => 'Login ou senha inválidos'], 401);
});

// PUT /usuarios/{id}/senha
$app->put('/usuarios/{id}/senha', function (Request $request, Response $response, array $args) use (&$usuarios) {
    $dados = $request->getParsedBody();

    if (
        !is_array($dados) ||
        !isset($dados['senha_atual'], $dados['nova_senha']) ||
        $dados['senha_atual'] === '' ||
        $dados['nova_senha'] === ''
    ) {
        return resposta($response, ['erro' => 'Informe a senha atual e a nova senha'], 400);
    }

    foreach ($usuarios as &$usuario) {
        if ($usuario['id'] == (int) $args['id']) {
            if ($usuario['senha'] !== $dados['senha_atual']) {
                return resposta($response, ['erro' => 'Senha atual incorreta'], 401);
            }

            $usuario['senha'] = $dados['nova_senha'];

            return resposta($response, ['mensagem' => 'Senha alterada com sucesso']);
        }
    }

    return resposta($response, ['erro' => 'Usuário não encontrado'], 404);
});

$app->run();
