<?php

namespace AntonyThorpe\SilverShopProductModel\Tests;

use Exception;
use SilverShop\Page\ProductCategory;
use AntonyThorpe\SilverShopProductModel\ProductModel;
use SilverStripe\Dev\SapphireTest;

class SilvershopProductModelModelTest extends SapphireTest
{
    protected static $fixture_file = 'vendor/silvershop/core/tests/php/Fixtures/shop.yml';

    public function testNew(): void
    {
        $product_category = $this->objFromFixture(ProductCategory::class, "electronics");
        $new_product_model = ProductModel::create(['Title' => 'Freighter', 'Description' => 'Transportation across the Galaxy', 'ProductCategoryID' => $product_category->ID]);
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
            (int) $product_category->ID,
            $product_model->ProductCategoryID,
            'The ProductCategoryID is ' . $product_category->ID
        );
    }

    public function testRequiredFields(): void
    {
        // create an instance that lacks the required Title field
        $product_category = $this->objFromFixture(ProductCategory::class, "electronics");
        $new_product_model = ProductModel::create([
            //'Title' => 'Freighter',
            'Description' => 'Transportation across the Galaxy',
            'ProductCategoryID' => $product_category->ID,
        ]);
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

        // create an instance that lacks the required ProductCategoryID field to test error
        $new_product_model = ProductModel::create(['Title' => 'Freighter', 'Description' => 'Transportation across the Galaxy']);
        $writeFailed = false;
        try {
            $new_product_model->write();
        } catch (Exception) {
            $writeFailed = true;
        }
        $this->assertTrue(
            $writeFailed,
            "ProductModel should not be writable, since it doesn't contain the required ProductCategoryID field"
        );
    }
}
