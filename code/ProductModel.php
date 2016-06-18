<?php

/**
 * Create Product Models in the Product Category and use these for the dropdown when editing a product
 * (instead of a textfield).
 * In addition, loop over the models on the Product Category Page
 */
class ProductModel extends DataObject {

    private static $db = array(
        'Title' => 'Varchar(100)',
        'Description' => 'Varchar(255)',
        'Sort' => 'Int'
    );

    private static $has_one = array(
        'ProductCategory' => 'ProductCategory'
    );

    private static $required_fields = array(
        'Title'
    );

    public function getCMSFields()
    {
        return FieldList::create(
            TextField::create('Title', _t("ProductModel.TITLE", "Model Title"))
                ->setMaxLength(100),
            TextField::create('Description', _t("ProductModel.DESCRIPTION", "Model Description"))
                ->setRightTitle(_t('ProductModel.DESCRIPTIONRIGHTTITLE', 'An additional description if needed'))
                ->setMaxLength(255)
        );
    }

    public function validate()
    {
        $result = parent::validate();

        if (empty($this->Title)) {
            $result->error(
                _t(
                    "ProductModel.VALIDATIONMESSAGETITLE",
                    "ProductModel Class validation - missing the Model Title field"
                )
            );
        }

        if (empty($this->ProductCategoryID)) {
            $result->error(
                _t(
                    "ProductModel.VALIDATIONMESSAGEPRODUCTCATEGORYID",
                    "ProductModel Class validation - missing ProductCategoryID field"
                )
            );
        }

        return $result;
    }
}
