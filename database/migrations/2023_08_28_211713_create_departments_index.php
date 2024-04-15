<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use ScoutElastic\IndexConfigurator;
use ScoutElastic\Facades\ElasticClient;
use ScoutElastic\Migratable;

class CreateDepartmentsIndex extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        IndexConfigurator::defaultUsing(new DepartmentIndexConfigurator());

        ElasticClient::indices()
            ->create([
                'index' => config('scout.elasticsearch.index'),
                'body' => [
                    'settings' => [],
                    'mappings' => [
                        '_doc' => [
                            'properties' => [
                                'name' => [
                                    'type' => 'text',
                                ],
                                'description' => [
                                    'type' => 'text',
                                ],
                                // Add more properties as needed
                            ],
                        ],
                    ],
                ],
            ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        ElasticClient::indices()
            ->delete([
                'index' => config('scout.elasticsearch.index'),
            ]);
    }
}
