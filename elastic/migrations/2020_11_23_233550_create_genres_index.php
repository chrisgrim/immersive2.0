<?php
declare(strict_types=1);

use Elastic\Adapter\Indices\Mapping;
use Elastic\Adapter\Indices\Settings;
use Elastic\Migrations\Facades\Index;
use Elastic\Migrations\MigrationInterface;

final class CreateGenresIndex implements MigrationInterface
{
    /**
     * Run the migration.
     */
    public function up(): void
    {
        Index::create('genres', function (Mapping $mapping, Settings $settings) {
            $mapping->search_as_you_type('name');
            $mapping->text('slug');
            $mapping->integer('rank');
            $mapping->integer('priority');
        });
    }

    /**
     * Reverse the migration.
     */
    public function down(): void
    {
        Index::dropIfExists('genres');
    }
}
