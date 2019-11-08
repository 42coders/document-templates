<?php


namespace BWF\DocumentTemplates\TemplateDataSources;

use \ReflectionObject;
use \IteratorAggregate;

class TemplateDataSourceFactory
{
    /**
     * @param array|object|TemplateDataSourceInterface $data
     * @param string $name
     * @return TemplateDataSourceInterface
     */
    public static function build($data, $name = '')
    {
        if (is_object($data)) {
            $reflexionData = new ReflectionObject($data);
            if ($reflexionData->implementsInterface(TemplateDataSourceInterface::class)) {
                $data->setNamespace($name);
                return $data;
            }
        }

        if ($data instanceof IteratorAggregate) {
            return new IterableTemplateDataSource($data, $name);
        }

        return new TemplateDataSource($data, $name);
    }
}
