<?php

namespace AntonyThorpe\SilverShopProductModel;

use AntonyThorpe\SilverShopProductModel\ProductModel;
use SilverStripe\Forms\FieldList;
use SilverStripe\Forms\HeaderField;
use SilverStripe\Forms\LabelField;
use SilverStripe\Forms\GridField\GridFieldConfig_RecordEditor;
use SilverStripe\Forms\GridField\GridField;
use SilverStripe\ORM\ArrayList;
use SilverStripe\ORM\GroupedList;
use SilverStripe\ORM\DataExtension;
use Symbiote\GridFieldExtensions\GridFieldOrderableRows;

/**
 * Extends SilverShop\Page\ProductCategory to enable the adding of Models
 */
class ProductCategory extends DataExtension
{
    private static $has_many = array (
        'ProductModels' => ProductModel::class
    );

    public function updateCMSFields(FieldList $fields)
    {
        $fields->addFieldToTab(
            'Root.' . _t(\SilverShop\Page\ProductCategory::class . 'Models', 'Models'),
            HeaderField::create(
                'ModelHeading',
                _t(\SilverShop\Page\ProductCategory::class . 'ModelsHeading', 'Specify the models for this Product Category')
            )
        );
        $fields->addFieldToTab(
            'Root.' . _t(\SilverShop\Page\ProductCategory::class . 'Models', 'Models'),
            LabelField::create(
                'ModelLabel',
                _t(
                    \SilverShop\Page\ProductCategory::class . "ModelsLabel",
                    "The below entries determine the order of Models, and their associated products, on the Product Category Pages.  Also, sets the Models to appear in dropdown on each Product's page."
                )
            )
        );

        $fields->addFieldToTab(
            'Root.' . _t(\SilverShop\Page\ProductCategory::class . 'Models', 'Models'),
            GridField::create(
                'Models',
                _t(\SilverShop\Page\ProductCategory::class . 'Models', 'Models'),
                $this->owner->ProductModels()->sort('Sort', 'ASC'),
                $config = GridFieldConfig_RecordEditor::create()
            )
        );
        // Add reorder capabilties
        if ($this->owner->ProductModels()->count() >= 2) {
            $config->addComponent(new GridFieldOrderableRows('Sort'));
        }
    }

    /**
     * Create a grouped list for presentation in a table
     * @return GroupedList
     */
    public function getGroupedProductsByModel()
    {
        $list = $this->owner->ProductsShowable();
        $sortedList = new ArrayList();

        foreach ($this->owner->ProductModels()->sort('Sort') as $model) {
            foreach ($list as $product) {
                if ($product->Model == $model->Title) {
                    $sortedList->push($product);
                }
            }
        }

        return GroupedList::create($sortedList);
    }
}
