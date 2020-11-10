<?php


namespace BWF\DocumentTemplates\Support;

interface DumperInterface
{
    public function dump(string $text, array $data = []): array;
}
