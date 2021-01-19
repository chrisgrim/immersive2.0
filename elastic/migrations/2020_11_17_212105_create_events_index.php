<?php
declare(strict_types=1);

use ElasticAdapter\Indices\Mapping;
use ElasticAdapter\Indices\Settings;
use ElasticMigrations\Facades\Index;
use ElasticMigrations\MigrationInterface;

final class CreateEventsIndex implements MigrationInterface
{
    /**
     * Run the migration.
     */
    public function up(): void
    {
        Index::create('events', function (Mapping $mapping, Settings $settings) {
            $mapping->text('name', ['analyzer' => 'rebuilt_english']);
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
        Index::dropIfExists('events');
    }
}
