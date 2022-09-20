<?php
declare(strict_types=1);

use Elastic\Adapter\Indices\Mapping;
use Elastic\Adapter\Indices\Settings;
use Elastic\Migrations\Facades\Index;
use Elastic\Migrations\MigrationInterface;

final class CreateOrganizersIndex implements MigrationInterface
{
    /**
     * Run the migration.
     */
    public function up(): void
    {
        Index::create('organizers', function (Mapping $mapping, Settings $settings) {
            $mapping->search_as_you_type('name');
            $mapping->keyword('email');
            $mapping->integer('rank');
            $settings->analysis([
                'analyzer' => [
                    'standard_asciifolding' => [
                        'type' => 'custom',
                        'tokenizer' => 'standard',
                        'filter' => [ 'asciifolding' ]   
                    ]
                ]
            ]);
        });
    }

    /**
     * Reverse the migration.
     */
    public function down(): void
    {
        Index::dropIfExists('organizers');
    }
}
