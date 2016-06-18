<?php

class SilvershopProductModelModelTest extends SapphireTest
{
    protected static $fixture_file   = 'silvershop/tests/fixtures/shop.yml';
    protected static $disable_theme  = true;
    protected static $use_draft_site = true;

    public function testNew()
    {
        $product_category = $this->objFromFixture("ProductCategory", "electronics");
        $new_product_model = new ProductModel(
            array(
                'Title' => 'Freighter',
                'Description' => 'Transportation across the Galaxy',
                'ProductCategoryID' => $product_category->ID
            )
        );
        $id = $new_product_model->write();

        $product_model = ProductModel::get()->byID($id);

        $this->assertSame(
            'Freighter',
            $product_model->Title,
            'The Title is Freighter'
        );
        $this->assertSame(
            'Transportation across the Galaxy',
            $product_model->Description,
            'The Description is Transportation across the Galaxy'
        );
        $this->assertSame(
            $product_category->ID,
            (int) $product_model->ProductCategoryID,
            'The ProductCategoryID is ' . $product_category->ID
        );
    }

    public function testRequiredFields()
    {
        // create an instance that lacks the required Title field
        $product_category = $this->objFromFixture("ProductCategory", "electronics");
        $new_product_model = new ProductModel(
            array(
                //'Title' => 'Freighter',
                'Description' => 'Transportation across the Galaxy',
                'ProductCategoryID' => $product_category->ID
            )
        );
        $writeFailed = false;
        try {
            $new_product_model->write();
        } catch (Exception $ex) {
            $writeFailed = true;
        }
        $this->assertTrue(
            $writeFailed,
            "ProductModel should not be writable, since it doesn't contain the required Title field"
        );

        // create an instance that lacks the required ProductCategoryID field
        $product_category = $this->objFromFixture("ProductCategory", "electronics");
        $new_product_model = new ProductModel(
            array(
                'Title' => 'Freighter',
                'Description' => 'Transportation across the Galaxy',
                //'ProductCategoryID' => $product_category->ID
            )
        );
        $writeFailed = false;
        try {
            $new_product_model->write();
        } catch (Exception $ex) {
            $writeFailed = true;
        }
        $this->assertTrue(
            $writeFailed,
            "ProductModel should not be writable, since it doesn't contain the required ProductCategoryID field"
        );
    }
}
