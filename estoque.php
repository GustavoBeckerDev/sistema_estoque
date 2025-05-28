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
        'validade' => new DateTime('2025-05-31')
    ],
    [
        'nome' => 'Leite',
        'quantidade' => 15,
        'valor' => 8.90,
        'validade' => new DateTime('2025-06-01')
    ],
    [
        'nome' => 'Farinha de Trigo',
        'quantidade' => 8,
        'valor' => 31.50,
        'validade' => new DateTime('2025-06-03')
    ],
    [
        'nome' => 'Margarina Doriana',
        'quantidade' => 25,
        'valor' => 255.99,
        'validade' => new DateTime('2025-06-21')
    ],
    [
        'nome' => 'Bolacha da vaquinha',
        'quantidade' => 12,
        'valor' => 41.35,
        'validade' => new DateTime('2025-06-25')
    ],
    [
        'nome' => 'Doce de leite',
        'quantidade' => 21,
        'valor' => 189.99,
        'validade' => new DateTime('2025-06-21')
    ]
];

$foraDaValidade = [];
$promocao = [];

function menu() 
{
    echo "\n";
    echo "\033[36m===== MENU ESTOQUE =====\033[0m\n";
    echo "\n";
    echo "1. Listar produtos\n";
    echo "2. Adicionar produto\n";
    echo "3. Alterar produto\n";
    echo "4. Listar produtos fora da validade\n";
    echo "5. Lista produtos próximos do vencimento \n";
    echo "6. Remover produto\n";
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
    echo "\033[31m O produto {$estoque['nome']} foi removido com sucesso! \033[30\n";
}

function verificaValidade(&$estoque, &$foraDaValidade, &$promocao)
{
    global $dataatual;
    foreach ($estoque as $id => $produto) {
        $diferenca = $dataatual->diff($produto['validade']);
        $dias_para_vencer = $diferenca->days;

        if ($diferenca->invert == 1) { 
            $foraDaValidade[] = $produto;
            echo "\033[31m ***** Produto {$produto['nome']} com o ID: $id está fora da validade e foi removido do estoque. \033[0m\n";
            unset($estoque[$id]);
        } elseif ($diferenca->invert == 0 && $dias_para_vencer >= 1 && $dias_para_vencer <= 10) {
            $promocao[] = $produto;
            echo "\033[32m ***** Produto {$produto['nome']} com o ID: $id está perto do vencimento (em $dias_para_vencer dias) e foi adicionado à promoção. \033[0m\n";
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

function listarPromocao ($promocao)
{
   if (empty($promocao)) {
        echo "---------------------------------------------------------------------\n";
        echo "---------- A LISTA DE PRODUTOS NA PROMOÇÃO ESTÁ VAZIA ---------------\n";
        echo "---------------------------------------------------------------------\n";
        return;
    }
    
    echo "\033[31m-----------------------------------------------------------------------------------\033[0m\n";
    echo "\033[31m---------------------------- PRODUTOS NA PROMOÇÃO ---------------------------------\033[0m\n";
    echo "\033[31m-----------------------------------------------------------------------------------\033[0m\n";
    foreach ($promocao as $id => $produto) {
        echo "ID: $id | Nome: {$produto['nome']} | Qtd: {$produto['quantidade']} | Valor: R$ {$produto['valor']} | Validade: " . $produto['validade']->format('d/m/Y') . "\n";
    } 
}

//INICIO DO PROGRAMA, CHAMA A FUNÇÃO DE VERIFICAR VALIDADE E LOGO APÓS CHAMA O MENU NUM LOOP COM AS OPÇÕES QUE CHAMAM CADA FUNÇÃO:

echo "\n";
echo "\033[32m-------------------- SISTEMA DE ESTOQUE --------------------\033[0m\n";
echo "\n";
echo "\033[33mAntes de iniciar o programa, vamos verificar se tem algum produto fora da validade no estoque. \033[0m\n";
echo "\n";
verificaValidade($estoque, $foraDaValidade, $promocao);

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
            listarPromocao($promocao);
            break;
        case 6:
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