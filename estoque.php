<?php

$dataatual = new DateTime();

$estoque = 
[
    [   
        'cod' => 202501,
        'nome' => 'Arroz',
        'quantidade' => 10,
        'valor' => 22.50,
        'validade' => new DateTime('2025-05-16')
    ],
    [
        'cod' => 202502,
        'nome' => 'Feijão',
        'quantidade' => 15,
        'valor' => 8.90,
        'validade' => new DateTime('2025-05-21')
    ],
    [
        'cod' => 202503,
        'nome' => 'Açúcar',
        'quantidade' => 8,
        'valor' => 31.50,
        'validade' => new DateTime('2025-05-22')
    ],
    [
        'cod' => 202504,
        'nome' => 'Macarrão',
        'quantidade' => 10,
        'valor' => 22.50,
        'validade' => new DateTime('2025-06-08')
    ],
    [
        'cod' => 202505,
        'nome' => 'Leite',
        'quantidade' => 15,
        'valor' => 8.90,
        'validade' => new DateTime('2025-06-02')
    ],
    [
        'cod' => 202506,
        'nome' => 'Farinha de Trigo',
        'quantidade' => 8,
        'valor' => 31.50,
        'validade' => new DateTime('2025-06-04')
    ],
    [
        'cod' => 202507,
        'nome' => 'Margarina Doriana',
        'quantidade' => 25,
        'valor' => 255.99,
        'validade' => new DateTime('2025-06-22')
    ],
    [
        'cod' => 202508,
        'nome' => 'Bolacha da vaquinha',
        'quantidade' => 12,
        'valor' => 41.35,
        'validade' => new DateTime('2025-06-26')
    ],
    [
        'cod' => 202509,
        'nome' => 'Doce de leite',
        'quantidade' => 21,
        'valor' => 189.99,
        'validade' => new DateTime('2025-06-22')
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

function listar(&$estoque) 
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

    //$cods = array_column($estoque, 'cod');
    // print_r($cods);
    // sort($cods);
    // print_r($cods);
    // array_multisort($estoque, $cods);

    // FUNÇÃO USORT QUE TEM COMO PARÂMETRO O ARRAY ESTOQUE E LEVA UMA FUNCTION DE CALLBACK COM PARAMETRO $A E $B
    // FAZ A COMPARAÇÃO COM O OPERADOR SPACESHIP <=> E ORDENA COM O UASORT (UASORT NAO REINDEXA O ARRAY DIFERENTE DO USORT)
    // PRIMEIRO ORDENA O PRODUTO E LOGO APÓS ORDENA A DATA.
    // OS RETURN SÃO OS CALLBACKS DA FUNCTION CRIADA LÁ EM CIMA, OBRIGATÓRIO USAR NESSE TIPO DE FUNÇÃO, NEM QUE SEJA NULO...
    // ANTES DE INICIAR A ORDENAÇÃO, EU PASSO A FUNÇÃO DE VERIFICA VALIDADE PARA REMOVER PRODUTOS E ADICIONAR A PROMOÇÃO

    uasort($estoque, function($a, $b) 
    {
    $codCompare = $a['cod'] <=> $b ['cod'];
        if ($codCompare !== 0){
            return $codCompare;
        }
            return $a['validade'] <=> $b['validade'];
    }
    );

    foreach ($estoque as $id => $produto) {
        echo "ID: $id | Codigo: {$produto['cod']} | Nome: {$produto['nome']} | Qtd: {$produto['quantidade']} | Valor: R$ {$produto['valor']} | Validade: " . $produto['validade']->format('d/m/Y') . "\n";
    }
}

function adicionar(&$estoque, &$promocao)
{
    $dataatual = new DateTime();
    $dataatual->setTime(0, 0, 0);

    $cod = readline("Código do produto: ");
    $nome = "";

    foreach ($estoque as $produto) {
        if ($cod == $produto['cod']) {
            $nome = $produto['nome'];
        }
    }
    if ($nome == "") {
        $nome = readline("Nome do produto: ");
    }
    $quantidade = (int)readline("Quantidade: ");
    $valor = (float)readline("Valor (ex: 10.50): ");
    $validadestr = readline("Data de validade Ex: AAAA-MM-DD: ");
    $validade = new DateTime($validadestr);
    $validade->setTime(0, 0, 0);

    $intervalo = $dataatual->diff($validade);
    $dias_para_vencer = (int)$intervalo->format('%r%a');

    if ($validade <= $dataatual) {
        echo "-----------------------------------------------------------------------------------\n";
        echo "Produto com validade vencida ou muito próxima, não pode ser adicionado ao estoque. \n";
        echo "-----------------------------------------------------------------------------------\n";
    } else {
        $estoque[] = [
            'cod' => $cod,
            'nome' => $nome,
            'quantidade' => $quantidade,
            'valor' => $valor,
            'validade' => $validade
        ];

        echo "\n\033[33m----------Produto adicionado com sucesso!----------\033[0m\n\n";
    }
}

function alterar(&$estoque, &$promocao) 
{
    $dataatual = new DateTime();
    listar($estoque);
    $id = (int) readline("Informe o ID do produto que deseja alterar: ");
    if (!isset($estoque[$id])) {
        echo "Produto não encontrado.\n";
        return;
    }
    $estoque[$id]['nome'] = readline("Alterar nome: ");
    $estoque[$id]['quantidade'] = (int) readline("Alterar quantidade: ");
    $estoque[$id]['valor'] = (float) readline("Alterar valor: ");
    
    $validadestr = readline("Data de validade Ex: AAAA-MM-DD: ");
    $validade = new DateTime($validadestr);
    
    if ($validade <= $dataatual){
        echo "-----------------------------------------------------------------------------------\n";
        echo "Produto com validade vencida ou muito próxima, não pode ser adicionado ao estoque. \n";
        echo "-----------------------------------------------------------------------------------\n";
    } else {
        $estoque[$id]['validade'] = $validade;
        
        $intervalo = $dataatual->diff($validade);
        $diasRestantes = (int) $intervalo->format('%r%a');

        if ($diasRestantes <= 10) {
            if (!estaNaPromocao($promocao, $id)) {
                $promocao[$id] = $estoque[$id];
                echo "Produto adicionado à promoção, faltam $diasRestantes dias para vencer.\n";
            }
        }
        echo "Produto alterado com sucesso!\n";
    }
}

function remover(&$estoque) 
{
    listar($estoque);
    $id = (int) readline("Informe o ID do produto que deseja remover: ");
    if (!isset($estoque[$id])) {
        echo "Produto não encontrado.\n";
        return;
    }

    echo "\033[31m O produto {$estoque['nome']} foi removido com sucesso! \033[30\n";
    unset($estoque[$id]);
}

function verificaValidade(&$estoque, &$foraDaValidade, &$promocao)
{
    $dataatual = new DateTime();
    $dataatual->setTime(0, 0, 0);  // ZERA AS HORAS/MINUTOS/SEGUNDO PARA A COMPARAÇÃO CORRETA COMPLETA EM DIAS
    $promocao = [];

    foreach ($estoque as $id => $produto) {
        $validade = clone $produto['validade'];
        $validade->setTime(0, 0, 0); // ZERA TAMBÉM A VALIDADE ADICIONADA AO PRODUTO

        $intervalo = $dataatual->diff($validade);
        $dias_para_vencer = (int)$intervalo->format('%r%a'); // diferença em dias com sinal

        if ($dias_para_vencer < 0) {
            $foraDaValidade[] = $produto;
            echo "\033[31m ***** Produto {$produto['nome']} com o ID: $id está fora da validade e foi removido do estoque. \033[0m\n";
            unset($estoque[$id]);
        } elseif ($dias_para_vencer >= 1 && $dias_para_vencer <= 10) {
            if (!estaNaPromocao($promocao, $id)) {
            $promocao[$id] = $produto;
        }
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
    
    echo "\033[32m-----------------------------------------------------------------------------------\033[0m\n";
    echo "\033[32m---------------------------- PRODUTOS NA PROMOÇÃO ---------------------------------\033[0m\n";
    echo "\033[32m-----------------------------------------------------------------------------------\033[0m\n";
    foreach ($promocao as $id => $produto) {
        echo "ID: $id | Nome: {$produto['nome']} | Qtd: {$produto['quantidade']} | Valor: R$ {$produto['valor']} | Validade: " . $produto['validade']->format('d/m/Y') . "\n";
    } 
}

function estaNaPromocao($promocao, $id) {
    return isset($promocao[$id]);
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
            verificaValidade($estoque, $foraDaValidade, $promocao);
            listar($estoque, $promocao);
            break;
        case 2:
            verificaValidade($estoque, $foraDaValidade, $promocao);
            adicionar($estoque, $promocao);
            break;
        case 3:
            verificaValidade($estoque, $foraDaValidade, $promocao);
            alterar($estoque, $promocao);
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