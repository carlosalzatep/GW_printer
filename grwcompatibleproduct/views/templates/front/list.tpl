{if isset($products) && isset($list_number) && $list_number }
    <section class="section" id="compatible_products" data-list-number="{$list_number}">
        <h3 class="h2 products-section-title page-product-heading">
            {if isset($module_title)}
                {$module_title}
            {/if}
        </h3>
        <div class="products splide splide-products" id="splide-products--compatibles" data-list-number="{$list_number}">
            <div class="splide__track">
                <ul class="splide__list">
                {foreach from=$products item="product" key="position"}
                    <li class="splide__slide">
                    {include file="catalog/_partials/miniatures/product.tpl" product=$product }
                    </li>
                {/foreach}
                </ul>
            </div>
        </div>
    </section>
{/if}