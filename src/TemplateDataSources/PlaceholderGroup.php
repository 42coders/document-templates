<?php


namespace BWF\DocumentTemplates\TemplateDataSources;

const TYPE_SINGLE_PLACEHOLDER = 'single';
const TYPE_ITERABLE_PLACEHOLDER = 'iterable';

class PlaceholderGroup implements PlaceholderGroupInterface, \JsonSerializable
{
    protected $name;
    protected $type;
    protected $placeholders;

    /**
     * Placeholder constructor.
     * @param $name
     * @param $placeholders
     * @param $type
     */
    public function __construct($name, $placeholders, $type)
    {
        $this->name = $name;
        $this->placeholders = $placeholders;
        $this->type = $type;
    }

    public function getName()
    {
        return $this->name;
    }

    public function getPlaceholders()
    {
        return $this->placeholders;
    }

    public function jsonSerialize()
    {
        return [
            'name' => $this->getName(),
            'type' => $this->type,
            'placeholders' => $this->getPlaceholders()
        ];
    }
}
