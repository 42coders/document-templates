<?php


namespace BWF\DocumentTemplates\DocumentTemplates;

class DocumentTemplateFactory
{
    /**
     * @param \BWF\DocumentTemplates\DocumentTemplates\DocumentTemplateModelInterface $model
     * @throws \Exception
     *
     * @return \BWF\DocumentTemplates\DocumentTemplates\DocumentTemplateInterface
     */
    public static function build(DocumentTemplateModelInterface $model)
    {
        /** @var DocumentTemplateInterface $instance */
        $instance = null;
        $class = $model->getDocumentClass();

        if (!class_exists($class) || !in_array(DocumentTemplateInterface::class, class_implements($class))) {
            throw new \Exception(
                sprintf(
                    'Document class: "%s" must implement "%s"',
                    $class,
                    DocumentTemplateInterface::class
                )
            );
        }

        $instance = new $class();
        $instance->init($model);

        return $instance;
    }
}
