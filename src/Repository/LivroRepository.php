<?php

declare(strict_types=1);

require_once __DIR__ . '/../Model/Livro.php';

final class LivroRepository
{
    public function __construct(private PDO $pdo) {}

    /**
     * Exercício 05: Listar livros ordenados por título
     * @return Livro[]
     */
    public function listarOrdenadosPorTitulo(): array
    {
        $stmt = $this->pdo->query('SELECT id, titulo, preco FROM livro ORDER BY titulo');
        $linhas = $stmt->fetchAll();

        $livros = [];
        foreach ($linhas as $linha) {
            $livros[] = new Livro(
                id: (int) $linha['id'],
                titulo: (string) $linha['titulo'],
                preco: (float) $linha['preco']
            );
        }

        return $livros;
    }

    /**
     * Exercícios 06 e 08: Buscar por ID com prepare/execute e tratar inexistente com null
     * Retorna ?Livro (convertendo a linha relacional para objeto de domínio)
     */
    public function buscarPorId(int $id): ?Livro
    {
        $stmt = $this->pdo->prepare('SELECT id, titulo, preco FROM livro WHERE id = :id');
        $stmt->execute(['id' => $id]);
        $linha = $stmt->fetch();

        if ($linha === false) {
            return null;
        }

        return new Livro(
            id: (int) $linha['id'],
            titulo: (string) $linha['titulo'],
            preco: (float) $linha['preco']
        );
    }

    /**
     * Exercício 07: Cadastrar livro com parâmetros nomeados
     */
    public function cadastrar(
        string $titulo,
        string $isbn,
        int $anoPublicacao,
        float $preco,
        int $quantidade
    ): void {
        $sql = 'INSERT INTO livro (titulo, isbn, ano_publicacao, preco, quantidade)
                VALUES (:titulo, :isbn, :ano, :preco, :quantidade)';

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([
            'titulo'     => $titulo,
            'isbn'       => $isbn,
            'ano'        => $anoPublicacao,
            'preco'      => $preco,
            'quantidade' => $quantidade,
        ]);
    }
}