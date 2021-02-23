{*
* 2007-2021 PrestaShop
*
* NOTICE OF LICENSE
*
* This source file is subject to the Academic Free License (AFL 3.0)
* that is bundled with this package in the file LICENSE.txt.
* It is also available through the world-wide-web at this URL:
* http://opensource.org/licenses/afl-3.0.php
* If you did not receive a copy of the license and are unable to
* obtain it through the world-wide-web, please send an email
* to license@prestashop.com so we can send you a copy immediately.
*
* DISCLAIMER
*
* Do not edit or add to this file if you wish to upgrade PrestaShop to newer
* versions in the future. If you wish to customize PrestaShop for your
* needs please refer to http://www.prestashop.com for more information.
*
*  @author    PrestaShop SA <contact@prestashop.com>
*  @copyright 2007-2021 PrestaShop SA
*  @license   http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
*  International Registered Trademark & Property of PrestaShop SA
*}

{*if isset($featuresOrderListFront)}
    {literal}
    <script type="text/javascript">
        const featuresOrderListFront = JSON.parse('{/literal}{$featuresOrderListFront|json_encode}{literal}');
    </script>
    {/literal}
{/if*}

{if isset($featuresOrderList)}

    <!-- {*$featuresOrderList|print_r*} -->

    <h2>Caractéristiques</h2>
    <input type="hidden" name="submitGrwfeaturesproductorderModuleExtra" value="1" />
    <div class="container">
        {foreach $featuresOrderList as $key => $fo}
            <div class="row">
                <div class="col-1">
                    <fieldset class="form-group mb-0">
                        <label class="form-control-label">Position</label>
                        <input type="hidden" id="id_feature-{$key}" name="id_feature-{$key}" value="{$fo.id_feature}">
                        <input type="number" id="order-{$key}" name="order-{$key}" class="form-control" value="{$fo.position}">
                    </fieldset>
                </div>
                <div class="col-4">
                    <fieldset class="form-group mb-0">
                        <label class="form-control-label">Caractéristique</label>
                        <input type="text" readonly id="name-{$key}" name="name-{$key}" class="form-control" value="{$fo.name}">
                    </fieldset>
                </div>
                <div class="col-7">
                    <fieldset class="form-group mb-0">
                        <label class="form-control-label">Valeur</label>
                        <input type="text" readonly id="value-{$key}" name="value-{$key}" class="form-control" value="{$fo.value}">
                    </fieldset>
                </div>
            </div>
        {/foreach}
    </div>

{/if}