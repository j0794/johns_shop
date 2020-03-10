{include file="header.tpl"}
	<div class="row">
		<div class="col-3 mb-4"><a href="/product/edit" class="btn btn-success">Добавить товар</a></div>
		<div class="col-9 mb-4 d-flex flex-column align-items-center">
			<form class="form-inline my-2 my-lg-0 ml-auto" action="/search">
				<div class="input-group input-group-sm mb-3">
					<input name="product_id" type="number" class="form-control" placeholder="Поиск по ID товара">
					<div class="input-group-append">
						<button class="btn btn-outline-secondary" type="submit" id="button-addon2">Найти</button>
					</div>
				</div>
			</form>
			<form class="form-inline my-2 my-lg-0 ml-auto" action="/search">
				<input name="name" class="form-control form-control-sm mr-sm-2" type="search" placeholder="Название" aria-label="Search">
				<div class="input-group input-group-sm mr-sm-2">
					<div class="input-group-prepend">
						<span class="input-group-text">Цена</span>
					</div>
					<input name="price_from" type="number" class="form-control" placeholder="от" >
					<input name="price_to" type="number" class="form-control" placeholder="до" >
				</div>
				<button class="btn btn-outline-success btn-sm my-2 my-sm-0" type="submit">Найти</button>
			</form>
		</div>
	</div>
	{if $paginator.pages > 1}
		{capture assign="pagination"}
		<nav>
			<ul class="pagination pagination-sm">
				{section start=1 loop=$paginator.pages+1 name="paginator"}
					<li class="page-item {if $smarty.section.paginator.iteration == $paginator.current}active{/if}">
						{if $smarty.section.paginator.iteration == $paginator.current}
							<span class="page-link">{$smarty.section.paginator.iteration}</span>
						{else}
							<a class="page-link" href="/search?{$paginator.get_params}&amp;page={$smarty.section.paginator.iteration}">{$smarty.section.paginator.iteration}</a>
						{/if}
					</li>
				{/section}
			</ul>
		</nav>
		{/capture}
		{$pagination}
	{/if}

	{if $results.items}
	<div class="h1 mb-3">Количество результатов: {$results.count}</div>
	<div class="row">
		{foreach from=$results.items item=result}
		<div class="col-md-4">
			<div class="card mb-4 shadow-sm">
				<div class="card-body">
					<div class="h5 card-title">
						<a href="/product/view/{$result.id}">{$result.name}</a>
					</div>
					<div class="card-text small">{$result.description}</div>
				</div>
				<ul class="list-group list-group-flush small">
					{assign var=product_vendor_id value=$result.vendor_id}
					<li class="list-group-item"><strong>Производитель:</strong> {$vendors[$product_vendor_id].name}</li>
					<li class="list-group-item"><strong>Категории:</strong> {foreach from=$result.folder_ids item=folder_id name=product_folder_ids}{$folders[$folder_id].name}{if !$smarty.foreach.product_folder_ids.last}, {/if}{foreachelse}&ndash;{/foreach}</li>
					<li class="list-group-item"><strong>Количество товара:</strong> {$result.amount}</li>
				</ul>
				<div class="card-body">
					<div class="d-flex justify-content-between align-items-center">
						<div class="btn-group">
							<a href="/product/edit/{$result.id}" class="btn btn-sm btn-outline-secondary">Редактировать</a>
							<a href="/product/buy/{$result.id}" class="btn btn-sm btn-outline-secondary">Купить</a>
						</div>
						<small class="text-muted">{$result.price}</small>
					</div>
				</div>
			</div>
		</div>
		{/foreach}
	</div>
	{else}
		<div class="h1 mb-3">Нет результатов</div>
	{/if}

{if $paginator.pages > 1}
	{$pagination}
{/if}

{include file="bottom.tpl"}