<?php include_once "conexao_obsoleta.php";

$IDs_registrados = [];

// Este arquivo está disponível por padrão em sua pasta JSON
$arquivo = file_get_contents('../JSON/dados_locais.json');
$data = json_decode($arquivo);

$verificar = "SELECT * from item";
$executa = $conexao->query($verificar);

// Salvando todos os IDs do banco num array
while($dados = $executa->fetch_assoc()){
    array_push($IDs_registrados, $dados["id_item"]);
}

// Registrando no banco os itens que só existem no JSON
foreach($data as $key => $value){
    
    $id_item = $value->id_item;
    $nome_icon = $value->nome_icon;
    $abamenu = $value->tipo_item;
    $nome = $value->nome_item;
    $coletavelsurvival = $value->coletavel;
    $nome_interno = $value->nome_interno;
    $empilhavel = $value->empilhavel;
    $versao = $value->versao_add;
    $renovavel = $value->renovavel;
    $aliases = $value->aliases;
    $descricao = $value->descricao;
    $oculto_invt = $value->oculto_invt;

    if($renovavel == null)
        $renovavel = 0;

    if($oculto_invt == null)
        $oculto_invt = 0;
    
    $versao = explode(".", $versao);

    if(!isset($versao[1]))
        $versao[1] = 0;
    
    if($versao[1] == 101)
        $versao[1] = 10;

    $versao = $versao[1];

    echo "$nome_icon <br><br>";

    if(!in_array($value->id_item, $IDs_registrados)){
        # Inserindo o item no banco de dados
        $insere = "INSERT into item (id_item, nome, abamenu, empilhavel, coletavelSurvival, nome_icon, renovavel, oculto_invt, versao_adicionada, nome_interno, aliases_nome, descricao) values ($id_item, '$nome', '$abamenu', $empilhavel, $coletavelsurvival, '$nome_icon', $renovavel, $oculto_invt, $versao, '$nome_interno', '$aliases', '$descricao');";
        $executa = $conexao->query($insere);
    }

    if(array_key_exists("cor_item", $value)){ // Verifica se existe os dados de cor do item
        $cor_item = $value->cor_item;
        
        $id_cor = $cor_item[0]->id_cor;
        $tipo_item = $cor_item[0]->tipo_item;

        $insere = "INSERT into cor_item (id_cor, id_item, tipo_item) values ($id_cor, $id_item, $tipo_item)";
        $executa = $conexao->query($insere);
    }
} 

Header("Location: ../index.php");