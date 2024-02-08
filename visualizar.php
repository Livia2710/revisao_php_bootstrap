<?php

//Incluir a conexão com o banco de dados;
include_once "conexao.php";


// Receber o ID  via método GET
$id = filter_input(INPUT_GET, "id", FILTER_SANITIZE_NUMBER_INT);

// Verificar se o ID não está vazia
if (!empty($id)) {

    // QUERY para selecionar informações do usuario e edereço associado
    $query_usuario = "SELECT usr.id, usr.nome, usr.email,
            ende.logradouro, ende.numero
            FROM usuarios AS usr 
            LEFT JOIN enderecos AS ende ON ende.usuario_id=usr.id
            WHERE usr.id=:id LIMIT 1";
    $result_usuario = $conn->prepare($query_usuario);
    $result_usuario->bindParam(':id', $id);
    $result_usuario->execute();

    // Verificar se a consulta foi bem-sucedida
    if (($result_usuario) and ($result_usuario->rowCount() != 0)) {
        //Recupera os dados do usuario e endereço associado
        $row_usuario = $result_usuario->fetch(PDO::FETCH_ASSOC);
        $retorna = ['statuts' => true, 'dados' => $row_usuario];
       
    } else {
        //Se não houver registros , retorna uma mensagem de erro
        $retorna = ['status' => false, 'msg' => "<div class='alert alert-danger' role='alert'>Erro: Nenhum usuário encontrado!</div>"];
    }
} else {
    //Se não o ID estiver vazio , retorna uma mensagem de erro
    $retorna = ['status' => false, 'msg' => "<div class='alert alert-danger' role='alert'>Erro: Nenhum usuário encontrado!</div>"];
}

// Retornar o resultado em formato JSON
echo json_encode($retorna);
?>