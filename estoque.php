<?php

$dataatual = new DateTime("");

$estoque = 
[
    [
        'nome' => 'Arroz',
        'quantidade' => 10,
        'valor' => 22.50,
        'validade' => new DateTime('2025-05-15')
    ],
    [
        'nome' => 'Feijão',
        'quantidade' => 15,
        'valor' => 8.90,
        'validade' => new DateTime('2025-10-20')
    ],
    [
        'nome' => 'Açúcar',
        'quantidade' => 8,
        'valor' => 31.50,
        'validade' => new DateTime('2025-05-21')
    ]
];

function menu() 
{
    echo "\n===== MENU ESTOQUE =====\n";
    echo "1. Listar produtos\n";
    echo "2. Adicionar produto\n";
    echo "3. Alterar produto\n";
    echo "4. Remover produto\n";
    echo "0. Sair\n";
    echo "Escolha uma opção: ";
}

function listar($estoque) 
{
    if (empty($estoque)) {
        echo "-----------------------------------\n";
        echo "---------- Estoque vazio ----------\n";
        echo "-----------------------------------\n";
        return;
    }

    echo "--------- PRODUTOS NO ESTOQUE ---------\n";
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
        echo "Produto com validade vencida ou muito próxima, não pode ser adicionado ao estoque. \n";
    } else {
    $estoque[] = ['nome' => $nome, 'quantidade' => $quantidade, 'valor' => $valor, 'validade' => $validade];
    echo "Produto adicionado com sucesso!\n";
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
            remover($estoque);
            break;
        case 0:
            echo "Saindo...\n";
            break;
        default:
            echo "Opção inválida!\n";
    }
} while ($opcao != 0);

?>