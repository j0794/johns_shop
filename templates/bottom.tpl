</div>
</div>
</div>
</div>
</main>

{if $user.id}
<div class="modal fade" id="profileModal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Редактирование профиля: {$user.name}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form method="post" action="/user/editing">
                        <input type="hidden" name="user_id" value="{$user.id}">
                        <div class="form-group">
                            <label for="name">Имя пользователя</label>
                            <input id="name" type="text" name="name" class="form-control" required value="{$user.name}">
                        </div>
                        <div class="form-group">
                            <label for="email">E-mail</label>
                            <input id="email" type="text" name="email" class="form-control" required value="{$user.email}">
                        </div>
                        <div class="form-group">
                            <label for="profilePassword">Пароль</label>
                            <input id="profilePassword" type="password" name="password" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label for="profilePasswordRepeat">Повторите пароль</label>
                            <input id="profilePasswordRepeat" type="password" name="password_repeat" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <button type="submit" class="btn btn-block btn-outline-dark">Сохранить</button>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <a href="/user/logout" class="btn btn-block btn-danger">Выход</a>
                </div>
            </div>
        </div>
    </div>
{else}
<div class="modal fade" id="loginModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Вход / Регистрация</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <ul role="tablist" class="nav nav-tabs mb-3">
                    <li class="nav-item"><a data-toggle="tab" href="#loginModalTabLogin" role="tab" id="loginModalLinkLogin" aria-controls="loginModalTabLogin" aria-selected="true" class="nav-link active">Вход</a></li>
                    <li class="nav-item"><a data-toggle="tab" href="#loginModalTabRegister" role="tab" id="loginModalLinkRegister" aria-controls="loginModalTabRegister" aria-selected="false" class="nav-link">Регистрация</a></li>
                </ul>
                <div class="tab-content">
                    <div id="loginModalTabLogin" role="tabpanel" aria-labelledby="loginModalLinkLogin" class="tab-pane fade px-3 active show">
                        <form method="post" action="/user/login">
                            <div class="form-group">
                                <label for="login" class="form-label">Логин</label>
                                <input id="login" name="login" type="text" required="" class="form-control">
                            </div>
                            <div class="form-group">
                                <label for="loginPassword" class="form-label">Пароль</label>
                                <input id="loginPassword" name="password" type="password" required="" class="form-control">
                            </div>
                            <div class="form-group">
                                <button type="submit" class="btn btn-block btn-outline-dark">Войти</button>
                            </div>
                        </form>
                    </div>
                    <div id="loginModalTabRegister" role="tabpanel" aria-labelledby="loginModalLinkRegister" class="tab-pane fade px-3">
                        <form method="post" action="/user/editing">
                            <input type="hidden" name="user_id" value="{$user.id}">
                            <div class="form-group">
                                <label for="name">Имя пользователя</label>
                                <input id="name" type="text" name="name" class="form-control" required value="{$user.name}">
                            </div>
                            <div class="form-group">
                                <label for="email">E-mail</label>
                                <input id="email" type="text" name="email" class="form-control" required value="{$user.email}">
                            </div>
                            <div class="form-group">
                                <label for="registerPassword">Пароль</label>
                                <input id="registerPassword" type="password" name="password" class="form-control" required>
                            </div>
                            <div class="form-group">
                                <label for="registerPasswordRepeat">Повторите пароль</label>
                                <input id="registerPasswordRepeat" type="password" name="password_repeat" class="form-control" required>
                            </div>
                            <div class="form-group">
                                <button type="submit" class="btn btn-block btn-outline-dark">Зарегистрироваться</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
{/if}

<script src="https://code.jquery.com/jquery-3.4.1.slim.min.js" integrity="sha384-J6qa4849blE2+poT4WnyKhv5vZF5SrPo0iEjwBvKU7imGFAV0wwj1yYfoRSJoZ+n" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js" integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" crossorigin="anonymous"></script>

</body>
</html>