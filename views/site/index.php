<?php

/** @var yii\web\View $this */

$this->title = 'Ссылки';
?>
<div class="site-index">

    <div class="jumbotron text-center bg-transparent mt-5 mb-5">
        <h1 class="display-4">Создай короткую ссылку!</h1>
    </div>

    <div class="body-content">

        <div class="row">
            <label for="basic-url">Введите URL</label>
                <div class="input-group mb-3">
                    <input type="text" class="form-control" id="basic-url" aria-describedby="basic-addon3">
                </div>
                <div class="input-group mb-3">
                    <button type="button" class="btn btn-success" id='generate_link' onclick='generate_link()'>Сгенерировать</button>
                </div>

        </div>

        <div class="row">
            <label for="short-url">Короткая ссылка</label>
                <div class="input-group mb-3">
                    <input type="text" class="form-control" id="short-url" aria-describedby="basic-addon3">
                </div>
        </div>

         <div class="">
            <label for="short-url">QR</label>
                <img id='qr-image-container' width="150" />
        </div>
    </div>
</div>
