<?php

$dataatual = new DateTime("");

$estoque = 
[
    [   
        'cod' => 202522,
        'nome' => 'Arroz',
        'quantidade' => 10,
        'valor' => 22.50,
        'validade' => new DateTime('2025-05-15')
    ],
    [
        'nome' => 'Feijão',
        'quantidade' => 15,
        'valor' => 8.90,
        'validade' => new DateTime('2025-05-20')
    ],
    [
        'nome' => 'Açúcar',
        'quantidade' => 8,
        'valor' => 31.50,
        'validade' => new DateTime('2025-05-21')
    ],
    [
        'nome' => 'Macarrão',
        'quantidade' => 10,
        'valor' => 22.50,
        'validade' => new DateTime('2025-06-15')
    ],
    [
        'nome' => 'Leite',
        'quantidade' => 15,
        'valor' => 8.90,
        'validade' => new DateTime('2025-10-20')
    ],
    [
        'nome' => 'Farinha de Trigo',
        'quantidade' => 8,
        'valor' => 31.50,
        'validade' => new DateTime('2025-06-21')
    ]
];

$foraDaValidade = [];

function menu() 
{
    echo "\n";
    echo "\033[36m===== MENU ESTOQUE =====\033[0m\n";
    echo "\n";
    echo "1. Listar produtos\n";
    echo "2. Adicionar produto\n";
    echo "3. Alterar produto\n";
    echo "4. Listar produtos fora da validade\n";
    echo "5. Remover produto\n";
    echo "0. Sair\n";
    echo "Escolha uma opção: ";
    echo "\n";
}

function listar($estoque) 
{
    if (empty($estoque)) {
        echo "\n";
        echo "-----------------------------------\n";
        echo "---------- Estoque vazio ----------\n";
        echo "-----------------------------------\n";
        echo "\n";
        return;
    }

    echo "\n";
    echo "----------------------------------------------------------\n";
    echo "\033[32m------------------  PRODUTOS NO ESTOQUE ------------------\033[0m\n";
    echo "----------------------------------------------------------\n";
    echo "\n";
    foreach ($estoque as $id => $produto) {
        echo "ID: $id | Nome: {$produto['nome']} | Qtd: {$produto['quantidade']} | Valor: R$ {$produto['valor']} | Validade: " . $produto['validade']->format('d/m/Y') . "\n";
    }
}

function adicionar(&$estoque) 
{
    global $dataatual;
    $nome = readline("Nome do produto: ");
    $quantidade = (int) readline("Quantidade: ");
    $valor = (float) readline("Valor (ex: 10.50): ");
    $validadestr = readline("Data de validade Ex: AAAA-MM-DD: ");
    $validade = new DateTime($validadestr);

    if ($validade <= $dataatual){
        echo "-----------------------------------------------------------------------------------\n";
        echo "Produto com validade vencida ou muito próxima, não pode ser adicionado ao estoque. \n";
        echo "-----------------------------------------------------------------------------------\n";
    } else {
    $estoque[] = ['nome' => $nome, 'quantidade' => $quantidade, 'valor' => $valor, 'validade' => $validade];
        echo "\n";
        echo "Produto adicionado com sucesso!\n";
        echo "\n";
    }
}

function alterar(&$estoque) 
{
    global $dataatual;
    listar($estoque);
    $id = (int) readline("Informe o ID do produto que deseja alterar: ");
    if (!isset($estoque[$id])) {
        echo "Produto não encontrado.\n";
        return;
    }
    $estoque[$id]['nome'] = readline("Alterar nome: ");
    $estoque[$id]['quantidade'] = (int) readline("Alterar quantidade: ");
    $estoque[$id]['valor'] = (float) readline("Alterar valor: ");

    echo "Produto alterado com sucesso!\n";
}

function remover(&$estoque) 
{
    listar($estoque);
    $id = (int) readline("Informe o ID do produto que deseja remover: ");
    if (!isset($estoque[$id])) {
        echo "Produto não encontrado.\n";
        return;
    }

    unset($estoque[$id]);
    echo "Produto removido com sucesso!\n";
}

function verificaValidade(&$estoque, &$foraDaValidade)
{
    global $dataatual;
    foreach ($estoque as $id => $produto){
        if ($produto['validade'] < $dataatual) {
            $foraDaValidade[] = $produto;
            echo "\033[31m ***** Produto {$produto['nome']} com o ID: $id está fora da validade e foi removido do estoque. \033[0m\n";
            unset($estoque[$id]);
        }
    }
}

function listarforavalidade($foraDaValidade)
{
    if (empty($foraDaValidade)) {
        echo "---------------------------------------------------------------------\n";
        echo "---------- A LISTA DE PRODUTOS FORA DA VALIDADE ESTÁ VAZIA ----------\n";
        echo "---------------------------------------------------------------------\n";
        return;
    }
    
    echo "\033[31m-----------------------------------------------------------------------------------\033[0m\n";
    echo "\033[31m---------------------------- PRODUTOS FORA DA VALIDADE ----------------------------\033[0m\n";
    echo "\033[31m-----------------------------------------------------------------------------------\033[0m\n";
    foreach ($foraDaValidade as $id => $produto) {
        echo "ID: $id | Nome: {$produto['nome']} | Qtd: {$produto['quantidade']} | Valor: R$ {$produto['valor']} | Validade: " . $produto['validade']->format('d/m/Y') . "\n";
    }
}

//INICIO DO PROGRAMA, CHAMA A FUNÇÃO DE VERIFICAR VALIDADE E LOGO APÓS CHAMA O MENU NUM LOOP COM AS OPÇÕES QUE CHAMAM CADA FUNÇÃO:

echo "\n";
echo "\033[32m-------------------- SISTEMA DE ESTOQUE --------------------\033[0m\n";
echo "\n";
echo "\033[33mAntes de iniciar o programa, vamos verificar se tem algum produto fora da validade no estoque. \033[0m\n";
echo "\n";
verificaValidade($estoque, $foraDaValidade);

do {
    menu();
    $opcao = (int) readline();

    switch ($opcao) {
        case 1:
            listar($estoque);
            break;
        case 2:
            adicionar($estoque);
            break;
        case 3:
            alterar($estoque);
            break;
        case 4: 
            listarforavalidade($foraDaValidade);
            break;
        case 5:
            remover($estoque);
            break;
        case 0:
            echo "Saindo...\n";
            exit;
        default:
            echo "Opção inválida!\n";
            echo "Tente novamente \n";
    }
} while ($opcao != 0);

?>