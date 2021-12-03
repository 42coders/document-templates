<?php

namespace BWF\DocumentTemplates\Support;

use BWF\DocumentTemplates\Exceptions\InvalidTwigExtension;
use BWF\DocumentTemplates\Sandbox\SecurityPolicy;
use Twig\Environment;
use Twig\Extension\ExtensionInterface;
use Twig\Extension\SandboxExtension;
use Twig\Loader\ArrayLoader;
use Twig\Loader\LoaderInterface;
use Twig\Node\Expression\ConstantExpression;
use Twig\Node\Expression\FunctionExpression;
use Twig\Node\Expression\GetAttrExpression;
use Twig\Node\Expression\NameExpression;
use Twig\Node\Node;

class TwigDumper implements DumperInterface
{
    protected Environment $twig;

    public function dump(string $text, array $data = []): array
    {
        $loader = new ArrayLoader([
            'dumpable' => $text,
        ]);

        $this->twig = $this->createEnvironment($loader);

        $tokens = $this->twig->tokenize($loader->getSourceContext('dumpable'));
        $nodes  = $this->twig->parse($tokens);

        return $this->fill($this->getTwigVariableNames($nodes, $data), $data);
    }

    protected function getTwigVariableNames($nodes, $data = null): array
    {
        $variables = [];
        foreach ($nodes as $node) {
            if ($node instanceof FunctionExpression) {
                $name      = $node->getAttribute('name');
                /** @var SandboxExtension $sandbox */
                $sandbox = $this->twig->getExtension(SandboxExtension::class);
                $sandbox->enableSandbox();
                $sandbox->checkSecurity([], [], [$name]);

                $arguments = $this->getTwigFunctionArguments($node->getIterator()->offsetGet('arguments'));
                $result    = call_user_func_array($this->twig->getFunction($name)->getCallable(), $arguments);

                $variables += [$name.'('.implode(',', $arguments).')' => implode(',', $arguments)];
                if (!empty($result) && is_string($result)) {
                    $variables += app(TwigDumper::class)->dump($result, $data);
                }
            } elseif ($node instanceof NameExpression) {
                $name             = $node->getAttribute('name');
                $variables[$name] = $name;
            } elseif ($node instanceof ConstantExpression && $nodes instanceof GetAttrExpression) {
                $value = $node->getAttribute('value');
                if (!empty($value) && is_string($value)) {
                    $variables[$value] = $value;
                }
            } elseif ($node instanceof GetAttrExpression) {
                $path = implode('.', $this->getTwigVariableNames($node, $data));
                if (!empty($path)) {
                    $variables[$path] = $path;
                }
            } elseif ($node instanceof Node) {
                $variables += $this->getTwigVariableNames($node, $data);
            }
        }

        return $variables;
    }

    protected function getTwigFunctionArguments($nodes): array
    {
        $variables = [];
        foreach ($nodes as $node) {
            if ($node instanceof ConstantExpression) {
                $value = $node->getAttribute('value');
                if (!empty($value)) {
                    $variables[] = $value;
                }
            }
        }

        return $variables;
    }

    protected function fill(array $placeholders, array $data): array
    {
        $result  = [];
        $flatten = $this->dot($data);
        foreach ($placeholders as $key => $value) {
            if (is_array($value)) {
                $result[$key] = $this->fill($value, $data);
            } else {
                $result[$key] = $flatten[$key] ?? null;
            }
        }

        return $result;
    }

    /**
     * Flatten a multi-dimensional associative array with dots, skip the "numeric" arrays.
     * Here we made an assumption that if the array has 0-th element than it is numeric.
     *
     * @param iterable $array
     * @param string   $prepend
     *
     * @return array
     */
    protected function dot($array, $prepend = '')
    {
        $results = [];

        foreach ($array as $key => $value) {
            if (is_array($value) && !empty($value) && !isset($value[0])) {
                $results = array_merge($results, static::dot($value, $prepend.$key.'.'));
            } else {
                $results[$prepend.$key] = $value;
            }
        }

        return $results;
    }

    /**
     * Creates the twig environment and sets the sets up the security policy.
     */
    private function createEnvironment(LoaderInterface $loader): Environment
    {
        $twig = new Environment($loader, config('document_templates.twig.environment'));

        foreach ($this->getExtensions() as $extension) {
            if (!in_array(ExtensionInterface::class, class_implements($extension))) {
                throw new InvalidTwigExtension($extension.' does not implement the required '.ExtensionInterface::class.' interface.');
            }
            if ($twig->hasExtension($extension)) {
                continue;
            }
            $twig->addExtension(app($extension));
        }

        $policy = new SecurityPolicy(
            config('document_templates.template_sandbox.allowedTags'),
            config('document_templates.template_sandbox.allowedFilters'),
            config('document_templates.template_sandbox.allowedMethods'),
            config('document_templates.template_sandbox.allowedProperties'),
            config('document_templates.template_sandbox.allowedFunctions')
        );
        $sandbox = new SandboxExtension($policy);
        $twig->addExtension($sandbox);

        return $twig;
    }

    /**
     * Gets the set extensions from the config.
     *
     * @return array
     */
    private function getExtensions(): array
    {
        return config('document_templates.twig.extensions', []);
    }
}
