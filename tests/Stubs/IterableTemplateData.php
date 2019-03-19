<?php


namespace BWF\DocumentTemplates\Tests\Stubs;


use BWF\DocumentTemplates\TemplateDataSources\TemplateDataSource;
use BWF\DocumentTemplates\TemplateDataSources\TemplateDataSourceFactory;

trait IterableTemplateData
{
    protected $testUsers = [
        [
            'id' => '1',
            'name' => 'first user name',
        ],
        [
            'id' => '2',
            'name' => 'second user name'
        ],
    ];

    protected $testOrders = [
        [
            'id' => '1',
            'description' => 'first order description',
        ],
        [
            'id' => '2',
            'description' => 'second order description'
        ],
    ];

    /**
     * @return TemplateDataSource[]
     */
    protected function getTestUsers()
    {
        $dataSources = [];

        foreach ($this->testUsers as $item) {
            $dataSources[] = TemplateDataSourceFactory::build($item, 'user');
        }

        return collect($dataSources);
    }

    /**
     * @return TemplateDataSource[]
     */
    protected function getTestOrders()
    {
        $dataSources = [];

        foreach ($this->testOrders as $item) {
            $dataSources[] = TemplateDataSourceFactory::build($item, 'order');
        }

        return collect($dataSources);
    }
}