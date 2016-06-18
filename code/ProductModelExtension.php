<?php
/**
 * Add model dropdown to Product where available
 * @package Product
 */
class ProductModelExtension extends DataExtension
{

    public function updateCMSFields(FieldList $fields)
    {

        // if there are Models set in the Product Category then use a dropdown to select
        if ($this->owner->Parent && $this->owner->Parent->ProductModels()->count()) {
            $fields->replaceField(
                'Model',
                DropdownField::create(
                    'Model',
                    _t("Product.MODELREQUIRED", "Model (required)"),
                    ArrayLib::valuekey($this->owner->Parent->ProductModels()->column('Title'))
                )
                    ->setEmptyString(_t("Product.MODELSELECT", "Select..."))
                    ->setAttribute('Required', true)
            );
        } else {

            // Update Model for extended length
            // see config.yml for updated db settings
            $model = $fields->dataFieldByName('Model');
            $model->setMaxLength(100);
        }
    }

    /**
     * For the template within the GroupedList, provide the model's Description recorded within the ProductModel Class
     * @return string description of the model
     */
    public function getModelDescription()
    {
        $productmodels = $this->owner->Parent->ProductModels();
        if ($productmodels->count() && $model = $this->owner->Model) {
            return $productmodels->find('Title', $model)->Description;
        }
    }
}
