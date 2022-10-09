<?php
//setcookie('product',2022,time()+300,'/');
?>
<script defer src="js/drag&drop.js?v=<?=filemtime(PROJECT.'/js/search.js')?>"></script>

<form id="product">
    <div>
        <label>Название товара</label>
        <input name="name" type="text">
    </div>
    <div>
        <label>Цена товара</label>
        <input name="price" type="text">
    </div>
    <div>
        <label>Количество товара</label>
        <input type="text">
    </div>
    <div>
        <label>Артикул товара</label>
        <input name="article" type="text">
    </div>
    <div>
        <label>Категория</label>
        <select name="category">
            <option value="1">Мужчины</option>
            <option value="2">Женщины</option>
            <option value="3">Дети</option>
        </select>
    </div>
    <div>
        <label>Описание</label>
        <textarea name="description"></textarea>
    </div>

    <div class="box-zone">
        <div class="drag-zone">
            <div class="drag-zone__title">Перетащите файлы или нажмите для загрузки....</div>
            <input id="setFiles" hidden type="file" multiple="multiple">
        </div>
        <!-- <div id="btn_upload" class="btn-upload">Загрузить</div> -->
    </div>
    <div class="drag-preview"></div>

    <div>
        <input type="submit" value="Добавить товар">
    </div>
</form>

<script>

    product.querySelector('input[type=submit]').onclick = async function (event) {
        // отмена страндартного поведения кнопки
        event.preventDefault();
        // собираем все данные с формы и помещаем в переменную
        let form = new FormData(product)
        // добавляем ключ action = addProduct
        // по нему мы определяем в handler.php 
        // какое действие мы хотим выполнить
        form.append('action', 'addProduct')

        $.ajax({
            url: 'core/handler.php',
            method: 'POST',
            processData: false,
            cache: false,
            contentType: false,
            data: form,
            success: async function (response, status, xhr) {
                if (xhr.status == 200) {

                    if (response) {
                        alert('Товар был успешно добавлен!')
                        let res = await uploadFiles(response)

                        if (res) {
                            alert('Изображения были успешно загружены!')
                        }
                    }

                }
            },
            error: function (obj, status, error) {
                console.error("Ошибка " + error);
            },
        })
    }


</script>