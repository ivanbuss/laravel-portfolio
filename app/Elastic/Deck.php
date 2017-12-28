<?php
/**
 * Created by PhpStorm.
 * User: usever
 * Date: 18.08.16
 * Time: 20:39
 */

namespace App\Elastic;

use App\Deck as DeckModel;
use \DB;


class Deck extends Document {

    protected $indexName = 'deck_index';

    protected $typeName = 'deck';

    /**
     * The elasticsearch settings.
     *
     * @var array
     */
    protected static $indexSettings = [
        "analysis" => [
            "char_filter" => [
                "replace" => [
                    "type" => "mapping",
                    "mappings" => [
                        "&=> and "
                    ],
                ],
            ],
            "filter" => [
                "word_delimiter" => [
                    "type" => "word_delimiter",
                    "split_on_numerics" => false,
                    "split_on_case_change" => true,
                    "generate_word_parts" => true,
                    "generate_number_parts" => true,
                    "catenate_all" => true,
                    "preserve_original" => true,
                    "catenate_numbers" => true,
                ]
            ],
            "analyzer" => [
                "default" => [
                    "type" => "custom",
                    "char_filter" => [
                        "html_strip",
                        "replace",
                    ],
                    "tokenizer" => "whitespace",
                    "filter" => [
                        "lowercase",
                        "word_delimiter",
                    ],
                ],
            ],
        ],
    ];

    protected static $mappingProperties = [
        "created_at" => [
            "type" => "date",
            "format" => "yyyy-MM-dd HH:mm:ss||yyyy-MM-dd||epoch_millis",
        ],
        "updated_at" => [
            "type" => "date",
            "format" => "yyyy-MM-dd HH:mm:ss||yyyy-MM-dd||epoch_millis",
        ],

        "name" => [
            "type" => "string",
        ],
        "description" => [
            "type" => "string",
        ],
        "edition" => [
            "type" => "string",
        ],
        "release_year" => [
            "type" => "string", // Integer
        ],
        "customization" => [
            "type" => "string",
        ],
        "prod_run" => [
            "type" => "string",
        ],
        "finish" => [
            "type" => "string",
        ],


        "artist" => [
            "type" => "string",
        ],
        "company" => [
            "type" => "string",
        ],
        "brand" => [
            "type" => "string",
        ],
        "manufacturer" => [
            "type" => "string",
        ],

        "features" => [
            "type" => "string",
        ],
        "themes" => [
            "type" => "string",
        ],
        "styles" => [
            "type" => "string",
        ],
        "stocks" => [
            "type" => "string",
        ],
        "colors" => [
            "type" => "string",
        ],
        "tags" => [
            "type" => "string",
        ],
    ];

    public function __construct(DeckModel $model) {
        parent::__construct($model);
    }

    /**
     * Elastic document index name
     * @return string
     */
    public static function getIndexName() {
        return "deck_index";
    }

    /**
     * Elastic document type name
     * @return string
     */
    public static function getTypeName() {
        return "deck";
    }

    /**
     * @return array
     * @todo make it private
     */
    public function getBody() {
        return [
            "created_at" => $this->model->created_at->format("Y-m-d H:m:s"),
            "updated_at" => $this->model->updated_at->format("Y-m-d H:m:s"),

            "name" => $this->model->name,
            "description" => $this->model->description,
            "edition" => $this->model->edition,
            "release_year" => $this->model->release_year,
            "customization" => $this->model->customization,
            "prod_run" => $this->model->prod_run,
            "finish" => $this->model->finish,

            "artist" => $this->model->artist->name,
            "company" => $this->model->company->name,
            "brand" => $this->model->brand->name,
            "manufacturer" => $this->model->manufacturer->name,

            "features" => $this->model->features()->lists('terms.value')->toArray(),
            "themes" => $this->model->themes()->lists('terms.value')->toArray(),
            "styles" => $this->model->styles()->lists('terms.value')->toArray(),
            "stocks" => $this->model->stock()->lists('terms.value')->toArray(),
            "colors" => $this->model->colors()->lists('terms.value')->toArray(),
            "tags" => $this->model->tags()->lists('terms.value')->toArray(),
        ];

    }

    public static function prepareBulkIndex() {
        $result = DB::select(DB::raw("
              SELECT d.id, 
              /* fields */
              d.name, d.edition, d.description, d.customization, d.release_year, d.prod_run, d.finish, d.created_at, d.updated_at,
              /* Referenced models */
              c.name as company,
              m.name as manufacturer,
              a.name as artist,
              b.name as brand,

              /* tags */
              dsc.colors, dsf.features, dst.tags, dsth.themes, dscs.stocks, dss.styles
              FROM decks d
              /* Comppany */
              LEFT JOIN companies AS c ON c.id = d.company_id
              /* manufacturer */
              LEFT JOIN manufacturer AS m ON m.id = d.printer_id
              /* artist */
              LEFT JOIN artists AS a ON a.id = d.artist_id
              /* brands */
              LEFT JOIN brands AS b ON a.id = d.collection_id
              
              
              /* Colors */
              LEFT JOIN (
                    SELECT deck_colors.deck_id, GROUP_CONCAT(terms.value) as colors
                    FROM deck_colors
                    LEFT JOIN terms ON terms.term_id = deck_colors.term_id
                    GROUP BY deck_colors.deck_id
              ) AS dsc ON d.id = dsc.deck_id
              
              /* Features */
              LEFT JOIN (
                    SELECT deck_features.deck_id, GROUP_CONCAT(terms.value) as features
                    FROM deck_features
                    LEFT JOIN terms ON terms.term_id = deck_features.term_id
                    GROUP BY deck_features.deck_id
              ) AS dsf ON d.id = dsf.deck_id
              
              /* Tags */
              LEFT JOIN (
                    SELECT deck_tags.deck_id, GROUP_CONCAT(terms.value) as tags
                    FROM deck_tags
                    LEFT JOIN terms ON terms.term_id = deck_tags.term_id
                    GROUP BY deck_tags.deck_id
              ) AS dst ON d.id = dst.deck_id
              
              /* Themes */
              LEFT JOIN (
                    SELECT deck_themes.deck_id, GROUP_CONCAT(terms.value) as themes
                    FROM deck_themes
                    LEFT JOIN terms ON terms.term_id = deck_themes.term_id
                    GROUP BY deck_themes.deck_id
              ) AS dsth ON d.id = dsth.deck_id
        
              /* Stock */
              LEFT JOIN (
                    SELECT deck_stock.deck_id, GROUP_CONCAT(terms.value) as stocks
                    FROM deck_stock
                    LEFT JOIN terms ON terms.term_id = deck_stock.term_id
                    GROUP BY deck_stock.deck_id
              ) AS dscs ON d.id = dscs.deck_id
        
              /* Style */
              LEFT JOIN (
                    SELECT deck_style.deck_id, GROUP_CONCAT(terms.value) as styles
                    FROM deck_style
                    LEFT JOIN terms ON terms.term_id = deck_style.term_id
                    GROUP BY deck_style.deck_id
              ) AS dss ON d.id = dss.deck_id
              GROUP BY d.id"));
        $collectionTermsFields = collect(['colors', 'features', 'tags', 'themes', 'stocks', 'styles']);
        $params = ['body' => []];
        foreach ($result as $delta => $deck) {
            // Turn all terms into the arrays
            $collectionTermsFields->each(function ($term, $key) use ($deck) {
                if ($deck->{$term}) {
                    $deck->{$term} = explode(',', $deck->{$term});
                }
            });

            $params['body'][] = [
                'index' => [
                    '_index' => self::getIndexName(),
                    '_type' => self::getTypeName(),
                    '_id' => $deck->id,
                ]
            ];
            $params['body'][] = [
                "created_at" => $deck->created_at,
                "updated_at" => $deck->updated_at,

                "name" => $deck->name,
                "description" => $deck->description,
                "edition" => $deck->edition,
                "release_year" => $deck->release_year,
                "customization" => $deck->customization,
                "prod_run" => $deck->prod_run,
                "finish" => $deck->finish,

                "artist" => $deck->artist,
                "company" =>$deck->company,
                "brand" => $deck->brand,
                "manufacturer" => $deck->manufacturer,

                "features" => $deck->features,
                "themes" => $deck->themes,
                "styles" => $deck->styles,
                "stocks" => $deck->stocks,
                "colors" => $deck->colors,
                "tags" => $deck->tags,
            ];

        }
        return $params;

    }

}
