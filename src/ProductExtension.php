<?php

namespace AntonyThorpe\SilverShopProductModel;

use SilverStripe\Forms\FieldList;
use SilverStripe\ORM\ArrayLib;
use SilverStripe\Forms\DropdownField;
use SilverStripe\ORM\DataExtension;

/**
 * Add model dropdown to Product where available
 * Extends SilverShop\Page\Product
 */
class ProductExtension extends DataExtension
{
    public function updateCMSFields(FieldList $fields): void
    {
        // if there are Models set in the Product Category then use a dropdown to select
        if ($this->getOwner()->Parent && $this->getOwner()->Parent->ProductModels()->count()) {
            $fields->replaceField(
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
            $model = $fields->dataFieldByName('Model');
            $model->setMaxLength(100);
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
