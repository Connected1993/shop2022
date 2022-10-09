<?php
// Отображаться товары в зависимости от выбраной категории
// Мужчины
// Женщины
// Дети

?>


<link rel="stylesheet" href="css/filters.css">

<h1><?=$category?></h1>
<div class="filters">

    <div class="filters__category">
        <div class="filters__name" onclick="showFilter(this)">
            <span>Категория</span>
            <i class="fa fa-arrow-circle-down" aria-hidden="true"></i>
        </div>
        <ul class="d-none">
            <li data-id="1">Мужчины</li>
            <li data-id="2">Женщины</li>
            <li data-id="3">Дети</li>
            <li data-id="4">Собаки</li>
            <li data-id="5">Кошки</li>
        </ul>
        
    </div>

    <div class="filters__size">
        <div class="filters__name" onclick="showFilter(this)">
            <span>Размер</span>
            <i class="fa fa-arrow-circle-down" aria-hidden="true"></i>
        </div>
        <ul class="d-none">
            <li data-id="1">2XL</li>
            <li data-id="2">XL</li>
            <li data-id="3">L</li>
            <li data-id="4">M</li>
            <li data-id="5">S</li>
        </ul>
    </div>

    <div class="filters__price">
        <div class="filters__name" onclick="showFilter(this)">
            <span>Стоимость</span>
            <i class="fa fa-arrow-circle-down" aria-hidden="true"></i>
        </div>
        <ul class="d-none">
            <input type="number" placeholder="от" value="0">
            <input type="number" placeholder="до">
            <input type="submit" value="Применить">
        </ul>
    </div>

</div>


