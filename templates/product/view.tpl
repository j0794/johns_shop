{include file="header.tpl"}

<div class="h1 mb-3">{$product.name}</div>

<div class="row">
    <div class="col-md-4">
        <div class="card mb-4 shadow-sm">
            <div class="card-body">
                <div class="card-text small">{$product.description}</div>
            </div>
            <ul class="list-group list-group-flush small">
                {assign var=product_vendor_id value=$product.vendor_id}
                <li class="list-group-item"><strong>Производитель:</strong> {$vendors[$product_vendor_id].name}</li>
                <li class="list-group-item"><strong>Категории:</strong> {foreach from=$product.folder_ids item=folder_id name=product_folder_ids}{$folders[$folder_id].name}{if !$smarty.foreach.product_folder_ids.last}, {/if}{foreachelse}&ndash;{/foreach}</li>
                <li class="list-group-item"><strong>Количество товара:</strong> {$product.amount}</li>
            </ul>
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div class="btn-group">
                        <a href="/product/edit/{$product.id}" class="btn btn-sm btn-outline-secondary">Редактировать</a>
                        <a href="/product/buy/{$product.id}" class="btn btn-sm btn-outline-secondary">Купить</a>
                    </div>
                    <small class="text-muted">{$product.price}</small>
                </div>
            </div>
        </div>
    </div>
</div>

{include file="bottom.tpl"}