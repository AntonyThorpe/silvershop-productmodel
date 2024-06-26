<?php
/**
 * Create Product Models in the Product Category and use these for the dropdown when editing a product
 * (instead of a textfield).
 * In addition, loop over the models on the Product Category Page
 */
namespace AntonyThorpe\SilverShopProductModel;

use SilverShop\Page\ProductCategory;
use SilverStripe\Forms\FieldList;
use SilverStripe\Forms\TextField;
use SilverStripe\ORM\DataObject;

class ProductModel extends DataObject
{
    /**
     * @config
     */
    private static array $db = [
        'Title' => 'Varchar(100)',
        'Description' => 'Varchar(255)',
        'Sort' => 'Int'
    ];

    /**
     * @config
     */
    private static array $has_one = [
        'ProductCategory' => ProductCategory::class
    ];

    /**
     * @config
     */
    private static array $required_fields = ['Title'];

    /**
     * @config
     */
    private static string $table_name = 'SilverShop_ProductModel';

    public function getCMSFields()
    {
        return FieldList::create(
            TextField::create('Title', _t(self::class . 'Title', 'Model Title'))
                ->setMaxLength(100),
            TextField::create('Description', _t(self::class . 'Description', 'Model Description'))
                ->setRightTitle(_t(self::class . 'DescriptionRightTitle', 'An additional description if needed by the website'))
                ->setMaxLength(255)
        );
    }

    public function validate()
    {
        $result = parent::validate();

        if (empty($this->Title)) {
            $result->addError(
                _t(
                    self::class . 'ValidationMessageTitle',
                    'ProductModel Class validation - missing the Model Title field'
                )
            );
        }

        if (empty($this->ProductCategoryID)) {
            $result->addError(
                _t(
                    self::class . 'ValidationMessageProductCategoryID',
                    'ProductModel Class validation - missing ProductCategoryID field'
                )
            );
        }

        return $result;
    }
}
