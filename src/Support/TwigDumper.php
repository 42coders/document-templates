<?php


namespace BWF\DocumentTemplates\Support;

use Twig\Environment;
use Twig\Loader\ArrayLoader;
use Twig\Node\Expression\ConstantExpression;
use Twig\Node\Expression\GetAttrExpression;
use Twig\Node\Expression\NameExpression;
use Twig\Node\Node;

class TwigDumper implements DumperInterface
{
    public function dump(string $text, array $data = []): array
    {
        $loader = new ArrayLoader([
            'dumpable' => $text
        ]);
        $twig = new Environment($loader);
        $tokens = $twig->tokenize($loader->getSourceContext('dumpable'));
        $nodes = $twig->parse($tokens);

        return $this->fill($this->getTwigVariableNames($nodes), $data);
    }

    protected function getTwigVariableNames($nodes): array
    {
        $variables = [];
        foreach ($nodes as $node) {
            if ($node instanceof NameExpression) {
                $name = $node->getAttribute('name');
                $variables[$name] = $name;
            } elseif ($node instanceof ConstantExpression && $nodes instanceof GetAttrExpression) {
                $value = $node->getAttribute('value');
                if (!empty($value) && is_string($value)) {
                    $variables[$value] = $value;
                }
            } elseif ($node instanceof GetAttrExpression) {
                $path = implode('.', $this->getTwigVariableNames($node));
                if (!empty($path)) {
                    $variables[$path] = $path;
                }
            } elseif ($node instanceof Node) {
                $variables += $this->getTwigVariableNames($node);
            }
        }
        return $variables;
    }

    protected function fill(array $placeholders, array $data): array
    {
        $result = [];
        $flatten = $this->dot($data);
        foreach ($placeholders as $key) {
            $result[$key] = $flatten[$key] ?? null;
        }

        return $result;
    }

    /**
     * Flatten a multi-dimensional associative array with dots, skip the "numeric" arrays.
     * Here we made an assumption that if the array has 0-th element than it is numeric.
     *
     * @param  iterable  $array
     * @param  string  $prepend
     * @return array
     */
    protected function dot($array, $prepend = '')
    {
        $results = [];

        foreach ($array as $key => $value) {
            if (is_array($value) && ! empty($value) && !isset($value[0])) {
                $results = array_merge($results, static::dot($value, $prepend.$key.'.'));
            } else {
                $results[$prepend.$key] = $value;
            }
        }

        return $results;
    }
}
