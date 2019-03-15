<?php


namespace BWF\DocumentTemplates\Tests\Stubs;


use BWF\DocumentTemplates\TemplateDataSources\TemplateDataSource;

trait ArrayTemplateData
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
            $dataSources[] = new TemplateDataSource($item, 'user');
        }

        return $dataSources;
    }

    /**
     * @return TemplateDataSource[]
     */
    protected function getTestOrders()
    {
        $dataSources = [];

        foreach ($this->testOrders as $item) {
            $dataSources[] = new TemplateDataSource($item, 'order');
        }

        return $dataSources;
    }
}