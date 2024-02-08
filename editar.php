<?php

// Incluir a conexão com o banco de dados
include_once "conexao.php";

// Receber os dados do formulário via método POST
$dados = filter_input_array(INPUT_POST, FILTER_DEFAULT);


//Validar o formulário
if (empty($dados['nome'])) {
    //Se o campo nome estiver vazio, retorna mensagemd e erro
    $retorna = ['status' => false, 'msg'=> "<div class='alert alert-danger' role='alert'>Erro: Necessário preencher o campo nome!</div>"];

} elseif (empty($dados['email'])) {
      //Se o campo email estiver vazio, retorna mensagemd e erro
    $retorna = ['status' => false, 'msg'=> "<div class='alert alert-danger' role='alert'>Erro: Necessário preencher o campo e-mail!</div>"];

} elseif (empty($dados['logradouro'])) {
      //Se o campo logradouro estiver vazio, retorna mensagemd e erro
    $retorna = ['status' => false, 'msg'=> "<div class='alert alert-danger' role='alert'>Erro: Necessário preencher o campo logradouro!</div>"];
    
} elseif (empty($dados['numero'])) {
      //Se o campo numero estiver vazio, retorna mensagemd e erro
    $retorna = ['status' => false, 'msg'=> "<div class='alert alert-danger' role='alert'>Erro: Necessário preencher o campo número!</div>"];
} else {
    //Editar no banco de dados na primeira tabela (tabela de usuarios)
    $query_usuario = "UPDATE usuarios SET nome=:nome, email=:email WHERE id=:id";
    $edit_usuario = $conn->prepare($query_usuario);
    $edit_usuario->bindParam(':nome', $dados['nome']);
    $edit_usuario->bindParam(':email', $dados['email']);
    $edit_usuario->bindParam(':id', $dados['id']);

    //Verificar se editou o usuario
    if ($edit_usuario->execute()) {

        //Editar no banco de dados na segunda tabela (tabela de endereços)
        $query_usuario = "UPDATE enderecos SET logradouro=:logradouro, numero=:numero WHERE usuario_id=:usuario_id";
        $edit_endereco = $conn->prepare($query_endereco);
        $edit_endereco->bindParam(':logradouro', $dados['logradouro']);
        $edit_endereco->bindParam(':numero', $dados['numero']);
        $edit_endereco->bindParam(':usuario_id', $dados['usuario_id']);

        //Verificar se editou o endereço
        if ($edit_endereco->execute()) {
            $retorna = ['status' => true, 'msg'=> "<div class='alert alert-sucess' role='alert'>Usuário editado com sucesso!</div>"];
        } else {
            $retorna = ['status' => false, 'msg'=> "<div class='alert alert-danger' role='alert'>Erro:Usuário não editado com sucesso!</div>"];
        }
    } else {
        $retorna = ['status' => false, 'msg'=> "<div class='alert alert-danger' role='alert'>Erro: Usuário não editado com sucesso!</div>"];
    }

}

// Retorna o resultado em formato JSON
echo json_encode($retorna);

?>