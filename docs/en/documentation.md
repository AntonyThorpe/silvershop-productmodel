# Documentation of SilverShop Product Model

## CMS
In the CMS open a Product Category page.  Click the `Model` tab and add a list of product models.  Drag and drop to re-order.

In any new child page, a dropdown is available to pick the appropriate model.

## Product Category Template
Loop over `$GroupedProductsByModel`.  Example below:
```html
<% if $ProductModels %>
    <% loop $GroupedProductsByModel.GroupedBy(Model) %>
        <% loop $Children.Filter('AllowPurchase', '1') %>
            <% if $First %>
                <div class="model-title">
                    <h2>$Model<% if $ModelDescription %> - $ModelDescription<% end_if %></h2>
                </div>
            <% end_if %>
            <% include SilverShop\Includes\ProductGroupItem %>
        <% end_loop %>
    <% end_loop %>
<% end_if %>
```
