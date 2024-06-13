<?php

namespace AntonyThorpe\SilverShopProductModel;

use SilverShop\Page\ProductCategory;
use AntonyThorpe\SilverShopProductModel\ProductModel;
use SilverStripe\Forms\FieldList;
use SilverStripe\Forms\GridField\GridField;
use SilverStripe\Forms\GridField\GridFieldConfig_RecordEditor;
use SilverStripe\Forms\HeaderField;
use SilverStripe\Forms\LabelField;
use SilverStripe\ORM\ArrayList;
use SilverStripe\ORM\DataExtension;
use SilverStripe\ORM\GroupedList;
use Symbiote\GridFieldExtensions\GridFieldOrderableRows;

/**
 * Extends SilverShop\Page\ProductCategory to enable the adding of Models
 */
class ProductCategoryExtension extends DataExtension
{
    /**
     * @config
     */
    private static array $has_many = [
        'ProductModels' => ProductModel::class
    ];

    public function updateCMSFields(FieldList $fields): void
    {
        $fields->addFieldToTab(
            'Root.' . _t(ProductCategory::class . 'Models', 'Models'),
            HeaderField::create(
                'ModelHeading',
                _t(ProductCategory::class . 'ModelsHeading', 'Specify the models for this Product Category')
            )
        );
        $fields->addFieldToTab(
            'Root.' . _t(ProductCategory::class . 'Models', 'Models'),
            LabelField::create(
                'ModelLabel',
                _t(
                    ProductCategory::class . "ModelsLabel",
                    "The below entries determine the order of Models, and their associated products, on the Product Category Pages.  Also, sets the Models to appear in dropdown on each Product's page."
                )
            )
        );

        $fields->addFieldToTab(
            'Root.' . _t(ProductCategory::class . 'Models', 'Models'),
            GridField::create(
                'Models',
                _t(ProductCategory::class . 'Models', 'Models'),
                $this->getOwner()->ProductModels()->sort('Sort', 'ASC'),
                $config = GridFieldConfig_RecordEditor::create()
            )
        );
        // Add reorder capabilties
        if ($this->getOwner()->ProductModels()->count() >= 2) {
            $config->addComponent(GridFieldOrderableRows::create('Sort'));
        }
    }

    /**
     * Create a grouped list for presentation in a table
     * @return GroupedList
     */
    public function getGroupedProductsByModel()
    {
        $list = $this->getOwner()->ProductsShowable();
        $sortedList = ArrayList::create();

        foreach ($this->getOwner()->ProductModels()->sort('Sort') as $model) {
            foreach ($list as $product) {
                if ($product->Model == $model->Title) {
                    $sortedList->push($product);
                }
            }
        }

        return GroupedList::create($sortedList);
    }
}
