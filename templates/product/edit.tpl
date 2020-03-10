{include file="header.tpl"}

<form action="/product/editing" method="post">
  <input type="hidden" name="product_id" value="{$product.id}">
  <div class="form-group">
    <label for="product_name">Название товара</label>
    <input id="product_name" type="text" name="name" class="form-control" required value="{$product.name}">
  </div>
  <div class="form-group">
    <label for="product_price">Цена</label>
    <input id="product_price" type="text" name="price" class="form-control" required value="{$product.price}">
  </div>
  <div class="form-group">
    <label for="product_amount">Количество</label>
    <input id="product_amount" type="number" name="amount" class="form-control" required value="{$product.amount}">
  </div>

  <div class="form-group">
    <label for="product_vendor">Производитель</label>
    <select class="form-control" name="vendor_id" id="product_vendor">
      <option value="0">-</option>
      {foreach from=$vendors item=e}
        <option {if $product.vendor_id == $e.id}selected{/if} value="{$e.id}">{$e.name}</option>
      {/foreach}
    </select>
  </div>
  <div class="form-group">
    <label for="product_folders">Категории</label>
    <select multiple class="form-control" name="folder_ids[]" id="product_folders">
      <option value="0">-</option>
      {foreach from=$folders item=e}
        <option {if in_array($e.id, $product.folder_ids)}selected{/if} value="{$e.id}">{$e.name}</option>
      {/foreach}
    </select>
  </div>

  <div class="form-group">
    <label for="product_description">Описание товара</label>
    <textarea id="product_description" name="description" class="form-control" rows="3">{$product.description}</textarea>
  </div>

  <button type="submit" class="btn btn-primary mb-2">Сохранить</button>
</form>

{include file="bottom.tpl"}