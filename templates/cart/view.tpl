{include file="header.tpl"}
{if $cart->getItems()}
<div class="h1 mb-3">Корзина</div>
<div class="row">
    <div class="col">
        <table class="table">
            <thead>
                <tr>
                    <th scope="col" width="1">#</th>
                    <th scope="col">Товар</th>
                    <th scope="col" width="1">Цена</th>
                    <th scope="col" width="1">Количество</th>
                    <th scope="col" width="1">Сумма</th>
                    <th scope="col" width="1">&nbsp;</th>
                </tr>
            </thead>
            <tbody>
                {foreach from=$cart->getItems() item=item name=cart_items}
                {assign var=product value=$item->getProduct()}
                <tr>
                    <th scope="row">{$smarty.foreach.cart_items.iteration}</th>
                    <td>{$product.name}</td>
                    <td>{$product.price}</td>
                    <td>{$item->getAmount()}</td>
                    <td>{$item->getPrice()}</td>
                    <td>
                        <form style="display:inline-block;" action="/cart/deleting" method="post"><input type="hidden" name="product_id" value="{$product.id}"><input type="submit" class="btn btn-sm btn-danger ml-2" value="Удалить"/>
                        </form>
                    </td>
                </tr>
                {/foreach}
            </tbody>
        </table>
    </div>
</div>
{else}
    <div class="h1 mb-3">Корзина пуста</div>
{/if}

{include file="bottom.tpl"}