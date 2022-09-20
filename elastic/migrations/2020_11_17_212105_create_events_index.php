<?php
declare(strict_types=1);

use Elastic\Adapter\Indices\Mapping;
use Elastic\Adapter\Indices\Settings;
use Elastic\Migrations\Facades\Index;
use Elastic\Migrations\MigrationInterface;

final class CreateEventsIndex implements MigrationInterface
{
    /**
     * Run the migration.
     */
    public function up(): void
    {
        Index::create('events', function (Mapping $mapping, Settings $settings) {
            $mapping->search_as_you_type('name');
            $mapping->keyword('showtype');
            $mapping->keyword('status');
            $mapping->integer('rank');
            $mapping->integer('priority');
            $mapping->integer('category_id');
            $mapping->geoPoint('location_latlon');
            $mapping->boolean('hasLocation');
            $mapping->object('priceranges', ['properties' => ['price' => ['type' => 'integer']]]);
            $mapping->object('shows', ['properties' => ['date' => ['type' => 'date', 'format' => 'yyyy-MM-dd HH:mm:ss']]]);
            $mapping->object('genres', ['properties' => ['name' => ['type' => 'keyword']]]);
            $mapping->date('closingDate', ['format' => 'yyyy-MM-dd HH:mm:ss']);
            $mapping->date('published_at', ['format' => 'yyyy-MM-dd HH:mm:ss']);
        });
    }

    /**
     * Reverse the migration.
     */
    public function down(): void
    {
        Index::dropIfExists('events');
    }
}
