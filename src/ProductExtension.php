<?php

namespace AntonyThorpe\SilverShopProductModel;

use SilverShop\Page\Product;
use SilverStripe\Core\ArrayLib;
use SilverStripe\Core\Extension;
use SilverStripe\Forms\DropdownField;
use SilverStripe\Forms\FieldList;

/**
 * Add model dropdown to Product where available
 * Extends SilverShop\Page\Product
 * @extends Extension<(Product & static)>
 */
class ProductExtension extends Extension
{
    public function updateCMSFields(FieldList $fieldList): void
    {
        // if there are Models set in the Product Category then use a dropdown to select
        if ($this->getOwner()->Parent && $this->getOwner()->Parent->ProductModels()->count()) {
            $fieldList->replaceField(
                'Model',
                DropdownField::create(
                    'Model',
                    _t(self::class . 'ModelRequired', 'Model (required)'),
                    ArrayLib::valuekey($this->getOwner()->Parent->ProductModels()->column('Title'))
                )
                    ->setEmptyString(_t(self::class . 'ModelSelect', 'Select...'))
                    ->setAttribute('Required', true)
            );
        } else {
            // Update Model for extended length
            // see config.yml for updated db settings
            $model = $fieldList->dataFieldByName('Model');
            if (method_exists($model, 'setMaxLength')) {
                $model->setMaxLength(100);
            }
        }
    }

    /**
     * For the template within the GroupedList, provide the model's Description recorded within the ProductModel Class
     * @return string description of the model
     */
    public function getModelDescription(): string|null
    {
        if ($this->getOwner()->Parent) {
            $productmodels = $this->getOwner()->Parent->ProductModels();
            if ($productmodels->count() && $this->getOwner()->Model) {
                $model = $this->getOwner()->Model;
                return $productmodels->find('Title', $model)->Description;
            }
        }

        return null;
    }
}
