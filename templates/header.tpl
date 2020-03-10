<!doctype html>
<html lang="ru">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>John's Shop CMS</title>


    <!-- Bootstrap core CSS -->
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">


<meta name="theme-color" content="#563d7c">

{literal}
    <style>
      .bd-placeholder-img {
        font-size: 1.125rem;
        text-anchor: middle;
        -webkit-user-select: none;
        -moz-user-select: none;
        -ms-user-select: none;
        user-select: none;
      }

      @media (min-width: 768px) {
        .bd-placeholder-img-lg {
          font-size: 3.5rem;
        }
      }
      .small-cart table td {
        text-align: right;
      }
    </style>
{/literal}
    <!-- Custom styles for this template -->
{*     <link href="album.css" rel="stylesheet"> *}
  </head>
  <body>
    <header class="sticky-top">
      <div class="navbar navbar-light bg-white shadow-sm">
        <div class="container d-flex flex-column flex-md-row align-items-center p-3 px-md-4">
          <a href="/" class="navbar-brand my-0 mr-md-auto">
            <strong>Главная</strong>
          </a>
          <nav class="my-2 my-md-0 mr-md-3">
            <a href="/folder" class="p-2 text-dark">Категории</a>
            <a href="/vendor" class="p-2 text-dark">Производители</a>
          </nav>
          {if $user.id}
            <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#profileModal">
              {$user.name}
            </button>
          {else}
            <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#loginModal">
              Вход / Регистрация
            </button>
          {/if}
        </div>
      </div>
    </header>
    <main role="main">
      <div class="album py-5 bg-light">
        <div class="container">
          <div class="row">
            <div class="col-2 position-sticky" style="top: 8.5rem; height: calc(100vh - 11.5rem);">
              <div class="card small-cart">
                <div class="card-body">
                  <h5 class="card-title">Корзина</h5>
                  <table class="table table-sm small">
                    <tbody>
                      <tr>
                        <th>Товаров:</th>
                        <td>{$cart->getAmount()}</td>
                      </tr>
                      <tr>
                        <th>Позиций:</th>
                        <td>{$cart->getItemsCount()}</td>
                      </tr>
                      <tr>
                        <th>Сумма:</th>
                        <td>{$cart->getPrice()}</td>
                      </tr>
                    </tbody>
                  </table>
                  <a href="/cart" class="btn btn-block btn-sm btn-outline-success mb-3">Оформить</a>
                  <a href="/cart/clear" class="btn btn-block btn-sm btn-outline-danger">Очистить</a>
                </div>
              </div>
            </div>
            <div class="col-10">