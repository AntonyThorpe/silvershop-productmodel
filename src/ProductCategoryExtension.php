<?php

namespace AntonyThorpe\SilverShopProductModel;

use AntonyThorpe\SilverShopProductModel\ProductModel;
use SilverShop\Page\ProductCategory;
use SilverStripe\Core\Extension;
use SilverStripe\Forms\FieldList;
use SilverStripe\Forms\GridField\GridField;
use SilverStripe\Forms\GridField\GridFieldConfig_RecordEditor;
use SilverStripe\Forms\HeaderField;
use SilverStripe\Forms\LabelField;
use SilverStripe\Model\List\ArrayList;
use SilverStripe\Model\List\GroupedList;
use SilverStripe\ORM\HasManyList;
use Symbiote\GridFieldExtensions\GridFieldOrderableRows;

/**
 * Extends SilverShop\Page\ProductCategory to enable the adding of Models
 * @method HasManyList<ProductModel> ProductModels()
 * @extends Extension<(ProductCategory & static)>
 */
class ProductCategoryExtension extends Extension
{
    /**
     * @config
     */
    private static array $has_many = [
        'ProductModels' => ProductModel::class
    ];

    public function updateCMSFields(FieldList $fieldList): void
    {
        $fieldList->addFieldToTab(
            'Root.' . _t(ProductCategory::class . 'Models', 'Models'),
            HeaderField::create(
                'ModelHeading',
                _t(ProductCategory::class . 'ModelsHeading', 'Specify the models for this Product Category')
            )
        );
        $fieldList->addFieldToTab(
            'Root.' . _t(ProductCategory::class . 'Models', 'Models'),
            LabelField::create(
                'ModelLabel',
                _t(
                    ProductCategory::class . "ModelsLabel",
                    "The below entries determine the order of Models, and their associated products, on the Product Category Pages.  Also, sets the Models to appear in dropdown on each Product's page."
                )
            )
        );

        $fieldList->addFieldToTab(
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
        $dataList = $this->getOwner()->ProductsShowable();
        $arrayList = ArrayList::create();

        foreach ($this->getOwner()->ProductModels()->sort('Sort') as $hasManyList) {
            foreach ($dataList as $product) {
                if ($product->Model == $hasManyList->Title) {
                    $arrayList->push($product);
                }
            }
        }

        return GroupedList::create($arrayList);
    }
}
