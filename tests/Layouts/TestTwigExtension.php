<?php


namespace BWF\DocumentTemplates\Tests\Layouts;


use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class TestTwigExtension extends AbstractExtension
{
    public function getFunctions()
    {
        return [
            new TwigFunction('return_twig', function () {
                return 'Twig';
            }),
        ];
    }
}