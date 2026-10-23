<?php

declare(strict_types=1);

final class Livro
{
    public function __construct(
        public readonly int $id,
        public string $titulo,
        public float $preco
    ) {}
}