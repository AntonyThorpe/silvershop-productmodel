<?php
/**
 * Extend Product Category to enable the adding of Models
 *
 * @package ProductCategory
 */
class ProductModelCategoryExtension extends DataExtension
{
    private static $has_many = array (
        'ProductModels' => 'ProductModel'
    );

    public function updateCMSFields(FieldList $fields)
    {
        $fields->addFieldToTab(
            "Root." . _t('ProductCategory.MODELS', 'Models'),
            HeaderField::create(
                "ModelHeading",
                _t("ProductCategory.MODELSHEADING", "Specify the models for this Product Category")
            )
        );
        $fields->addFieldToTab(
            "Root." . _t('ProductCategory.MODELS', 'Models'),
            LabelField::create(
                "ModelLabel",
                _t(
                    "ProductCategory.MODELSLABEL",
                    "The below entries determine the order of Models, and their associated products, on the Product Category Pages.  Also, sets the Models to appear in dropdown on each Product's page."
                )
            )
        );

        $fields->addFieldToTab(
            "Root." . _t('ProductCategory.MODELS', 'Models'),
            GridField::create(
                'Models',
                _t("ProductCategory.MODELS", "Models"),
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
