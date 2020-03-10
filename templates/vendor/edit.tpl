{include file="header.tpl"}

<form action="/vendor/editing" method="post">
    <input type="hidden" name="vendor_id" value="{$vendor.id}">
    <div class="form-group">
        <label for="name">Название производителя</label>
        <input id="name" type="text" name="name" class="form-control" required value="{$vendor.name}">
    </div>

    <button type="submit" class="btn btn-primary mb-2">Сохранить</button>
</form>

{include file="bottom.tpl"}