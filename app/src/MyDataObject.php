<?php


use SilverStripe\GraphQL\QueryFilter\Filters\GreaterThanFilter;
use SilverStripe\ORM\Connect\MySQLSchemaManager;
use SilverStripe\ORM\DataObject;
use SilverStripe\ORM\Filters\PartialMatchFilter;
use SilverStripe\ORM\Search\SearchContext;

class MyDataObject extends DataObject{

    private static $db = [
        "Title" => "Varchar(255)",
        "Content" => "HTMLText",
    ];

    private static $indexes = [
        'SearchFields' => [
            'type' => 'fulltext',
            'columns' => ['Title', 'Content'],
        ]
    ];

    private static $create_table_options = [
        MySQLSchemaManager::ID => 'ENGINE=MyISAM'
    ];

    public function getCustomSearchContext()
    {
        $fields = $this->scaffoldSearchFields([
            'restrictFields' => ['Title','Content']
        ]);

        $filters = [
            'Title' => new PartialMatchFilter('Title'),
            'Content' => new GreaterThanFilter('Content')
        ];

        return new SearchContext(
            $this->class,
            $fields,
            $filters
        );
    }
}
