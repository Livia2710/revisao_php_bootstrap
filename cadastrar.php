<?php


// Incluir a conexão com o banco de dados
include_once "conexao.php";

// Receber os dados do formulário via método POST
$dados = filter_input_array(INPUT_POST, FILTER_DEFAULT);

//Validar o formulário
if (empty($dados['nome'])) {
    $retorna = ['status' => false, 'msg'=> "<div class='alert alert-danger' role='alert'>Erro: Necessário preencher o campo nome!"];
} elseif (empty($dados['email'])) {
    $retorna = ['status' => false, 'msg'=> "<div class='alert alert-danger' role='alert'>Erro: Necessário preencher o campo e-mail!"];
} elseif (empty($dados['logradouro'])) {
    $retorna = ['status' => false, 'msg'=> "<div class='alert alert-danger' role='alert'>Erro: Necessário preencher o campo logradouro!"];
} elseif (empty($dados['numero'])) {
    $retorna = ['status' => false, 'msg'=> "<div class='alert alert-danger' role='alert'>Erro: Necessário preencher o campo número!"];
} else {
    //Cadastrar no banco de dados na primeira tabela (tabela de usuarios)
    $query_usuario = "INSERT INTO usuarios (nome, email) VALUES (:nome, :email)";
    $card_usuario = $conn->prepare($query_usuario);
    $card_usuario->bindParam(':nome', $dados['nome']);
    $card_usuario->bindParam(':email', $dados['email']);
    $card_usuario->execute();

    //Verificar se o usuario foi cadastro com sucesso
    if ($card_usuario->rowCount()) {
        //Recuperar o último ID inserido (ID do usuário recém-cadastrada)
        $id_usuario = $conn->lastInsertId();

        //Cadatrar no banco de dados na segunda tabela (tabela de endereço) associada ao usuário
        $query_endereco = "INSERT INTO enderecos (logradouro, numero, usuario_id) VALUE (:logradouro, :numero, :usuario_id)";
        $card_endereco = $conn->prepare($query_endereco);
        $card_endereco->bindParam(':logradouro', $dados['logradouro']);
        $card_endereco->bindParam(':numero', $dados['numero']);
        $card_endereco->bindParam(':usuario_id', $usuario_id);
        $card_endereco->execute();

        //Verificar se o endereço foi cadatrado com sucesso
        if ($card_endereco->rowCount()) {
            //Se ambos, usuario e endereço, forem cadatrados com sucesso, retornar mensagem de sucesso
            $retorna = ['status' => true, 'msg'=> "<div class='alert alert-sucess' role='alert'>Usuário cadastrado com sucesso!</div>"];
        } else {
            //Se o usuario for cadatrado com sucesso, mas o endereço não, retornar mensagem de erro
            $retorna = ['status' => false, 'msg'=> "<div class='alert alert-danger' role='alert'> Erro: Usuário não cadastrado com sucesso!</div>"];
        }
    } else {
         //Se houver erro ao cadastrar o usuario, retornar mensagem de erro
         $retorna = ['status' => false, 'msg'=> "<div class='alert alert-danger' role='alert'> Erro: Usuário não cadastrado com sucesso!</div>"];
    }

}

// Retornar o resultado em formato JSON
echo json_encode($retorna);



?>
