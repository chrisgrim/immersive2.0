<?php
declare(strict_types=1);

use ElasticAdapter\Indices\Mapping;
use ElasticAdapter\Indices\Settings;
use ElasticMigrations\Facades\Index;
use ElasticMigrations\MigrationInterface;

final class CreateRemoteLocationsIndex implements MigrationInterface
{
    /**
     * Run the migration.
     */
    public function up(): void
    {
        Index::create('remote_locations', function (Mapping $mapping, Settings $settings) {
            $mapping->text('name', ['analyzer' => 'rebuilt_english']);
            $settings->analysis([
                'filter' => [
                    'english_stop' => [
                        'type' => 'stop',
                        'stopwords' => '_english_' 
                    ],
                    'english_stemmer' => [
                        'type' => 'stemmer',
                        'language' => 'english'
                    ],
                    'english_possessive_stemmer' => [
                        'type' => 'stemmer',
                        'languange' => 'possessive_english'
                    ]
                ], 
                'analyzer' => [
                    'rebuilt_english' => [
                        'type' => 'custom',
                        'tokenizer' => 'standard',
                        'filter' => [
                            'english_stop',
                            'lowercase',
                            'english_stemmer',
                            'english_possessive_stemmer'
                        ]
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
        Index::dropIfExists('remote_locations');
    }
}
