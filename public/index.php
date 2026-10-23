<?php

declare(strict_types=1);

require_once __DIR__ . '/../config/Database.php';
require_once __DIR__ . '/../src/Model/Livro.php';
require_once __DIR__ . '/../src/Repository/LivroRepository.php';

$drivers = ['mysql', 'pgsql'];

foreach ($drivers as $driver) {
    echo "========================================\n";
    echo "Testando SGBD: " . strtoupper($driver) . "\n";
    echo "========================================\n";

    try {
        // 1. Conectar ao SGBD escolhido (Exercício 03)
        $pdo = Database::conectar($driver);
        $repository = new LivroRepository($pdo);

        // 2. Cadastrar um livro com parâmetros nomeados (Exercício 07)
        // ISBN dinâmico apenas para evitar erro de chave duplicada ao rodar múltiplos testes
        $isbnTeste = '978-' . rand(1000000000, 9999999999);
        $repository->cadastrar(
            titulo: 'PHP e Bancos de Dados',
            isbn: $isbnTeste,
            anoPublicacao: 2026,
            preco: 95.00,
            quantidade: 8
        );
        echo "[OK] Livro cadastrado com sucesso (ISBN: {$isbnTeste}).\n\n";

        // 3. Listar livros ordenados por título (Exercício 05)
        echo "--- Lista de Livros (Ordenados por Título) ---\n";
        $livros = $repository->listarOrdenadosPorTitulo();
        foreach ($livros as $livro) {
            $tituloSeguro = htmlspecialchars($livro->titulo, ENT_QUOTES, 'UTF-8');
            $precoFormatado = number_format($livro->preco, 2, ',', '.');
            echo "[#{$livro->id}] {$tituloSeguro} - R$ {$precoFormatado}\n";
        }
        echo "\n";

        // 4. Buscar livro existente por ID (Exercício 06)
        $livroEncontrado = $repository->buscarPorId(1);
        if ($livroEncontrado !== null) {
            echo "[Busca ID 1] Encontrado: {$livroEncontrado->titulo} (R$ {$livroEncontrado->preco})\n";
        }

        // 5. Tratar livro inexistente sem erro fatal (Exercício 08)
        $livroInexistente = $repository->buscarPorId(999999);
        if ($livroInexistente === null) {
            echo "[Busca ID 999999] Livro não encontrado (tratado com retorno null sem erro fatal).\n\n";
        }

    } catch (PDOException $e) {
        echo "Erro real no {$driver}: " . $e->getMessage() . "\n\n";
        error_log("[{$driver}] Erro de banco de dados: " . $e->getMessage());
    }
    
}