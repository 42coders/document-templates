<?php


namespace BWF\DocumentTemplates\Sandbox;

use Twig\Markup;
use Twig\Sandbox\SecurityNotAllowedFilterError;
use Twig\Sandbox\SecurityNotAllowedFunctionError;
use Twig\Sandbox\SecurityNotAllowedMethodError;
use Twig\Sandbox\SecurityNotAllowedPropertyError;
use Twig\Sandbox\SecurityNotAllowedTagError;
use Twig\Sandbox\SecurityPolicyInterface;
use Twig\Template;

class SecurityPolicy implements SecurityPolicyInterface
{
    private $allowedTags;
    private $allowedFilters;
    private $allowedMethods;
    private $allowedProperties;
    private $allowedFunctions;

    public function __construct(
        array $allowedTags = [],
        array $allowedFilters = [],
        array $allowedMethods = [],
        array $allowedProperties = [],
        array $allowedFunctions = []
    ) {
        $this->allowedTags = $allowedTags;
        $this->allowedFilters = $allowedFilters;
        $this->setAllowedMethods($allowedMethods);
        $this->allowedProperties = $allowedProperties;
        $this->allowedFunctions = $allowedFunctions;
    }

    /**
     * @param array $tags
     * @codeCoverageIgnore
     */
    public function setAllowedTags(array $tags)
    {
        $this->allowedTags = $tags;
    }

    /**
     * @param array $filters
     * @codeCoverageIgnore
     */
    public function setAllowedFilters(array $filters)
    {
        $this->allowedFilters = $filters;
    }

    public function setAllowedMethods(array $methods)
    {
        $this->allowedMethods = [];
        foreach ($methods as $class => $m) {
            $this->allowedMethods[$class] = array_map('strtolower', \is_array($m) ? $m : [$m]);
        }
    }

    /**
     * @param array $properties
     * @codeCoverageIgnore
     */
    public function setAllowedProperties(array $properties)
    {
        $this->allowedProperties = $properties;
    }

    /**
     * @param array $functions
     * @codeCoverageIgnore
     */
    public function setAllowedFunctions(array $functions)
    {
        $this->allowedFunctions = $functions;
    }

    public function checkSecurity($tags, $filters, $functions): void
    {
        foreach ($tags as $tag) {
            if (!\in_array($tag, $this->allowedTags)) {
                throw new SecurityNotAllowedTagError(sprintf('Tag "%s" is not allowed.', $tag), $tag);
            }
        }

        foreach ($filters as $filter) {
            if (!\in_array($filter, $this->allowedFilters)) {
                throw new SecurityNotAllowedFilterError(sprintf('Filter "%s" is not allowed.', $filter), $filter);
            }
        }

        foreach ($functions as $function) {
            if (!\in_array($function, $this->allowedFunctions)) {
                throw new SecurityNotAllowedFunctionError(
                    sprintf('Function "%s" is not allowed.', $function),
                    $function
                );
            }
        }
    }

    public function checkMethodAllowed($obj, $method): void
    {
        if ($obj instanceof Template || $obj instanceof Markup) {
            return;
        }

        $allowed = false;
        $method = strtolower($method);
        foreach ($this->allowedMethods as $class => $methods) {
            if ($obj instanceof $class) {
                $allowed = in_array($method, $methods);

                break;
            }
        }

        if (!$allowed) {
            $class = get_class($obj);
            throw new SecurityNotAllowedMethodError(sprintf(
                'Calling "%s" method on a "%s" object is not allowed.',
                $method,
                $class
            ), $class, $method);
        }
    }

    public function checkPropertyAllowed($obj, $property): void
    {
        $allowed = false;

        if (!empty($this->allowedProperties) &&
            !empty($this->allowedProperties[0]) &&
            $this->allowedProperties[0] === '*') {
            return;
        }

        foreach ($this->allowedProperties as $class => $properties) {
            if ($obj instanceof $class) {
                $allowed = in_array($property, is_array($properties) ? $properties : [$properties]);
                    break;
            }
        }

        if (!$allowed) {
            $class = get_class($obj);
            throw new SecurityNotAllowedPropertyError(sprintf(
                'Calling "%s" property on a "%s" object is not allowed.',
                $property,
                $class
            ), $class, $property);
        }
    }
}
