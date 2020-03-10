{include file="header.tpl"}

<form action="/folder/editing" method="post">
    <input type="hidden" name="folder_id" value="{$folder.id}">
    <div class="form-group">
        <label for="name">Название категории</label>
        <input id="name" type="text" name="name" class="form-control" required value="{$folder.name}">
    </div>

    <button type="submit" class="btn btn-primary mb-2">Сохранить</button>
</form>

{include file="bottom.tpl"}