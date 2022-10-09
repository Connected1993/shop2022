// элемент ввода данных
const searchInput = $('.search input')
// задержка
const delay = 250
// элемент вывода данных
const searchResult = $('.search__result ')
// таймер
let searchTimer
// искомое значение
let searchText = ''

$(document).on('click',event=>{

    if (!event.target?.parentNode?.classList?.contains('search') )
    {
        searchResult.html('')
        searchInput.val('')
    }
})

searchResult.on('click',event=>{
    // если клик был по тегу LI
    if (event.target.nodeName == 'LI')
    {
        // вставляем город в Input
        searchInput.val( event.target.textContent )
        // очистили результат поиска
        searchResult.html('')
    } 
    // остановили всплытие события
    event.stopPropagation();
})

searchInput.on('keyup',event=>{
    // получаем значение из инпута
    //console.log(event.target.value)

    let body = new FormData
    // наполняем переменную данными, в виде ассоциативного массива
    body.append('action','search')
    body.append('search',searchInput.val() )

    // если ничего не изменилось и были нажаты клавиши (ctrl,стрелки и.т.д) 
    //то ничего не делаем
    if ( searchInput.val().length == 0 || searchText == searchInput.val() ) 
    {
        searchResult.html('')
        return false
    }
    
    // удаляем таймер
    clearTimeout(searchTimer)
    // создаем таймер и заносим его в переменную searchTimer
    searchTimer = setTimeout(()=>{
        $.ajax({
            url: 'core/handler.php',
            method:'POST',
            cache:false,
            processData:false,
            contentType:false,
            data: body,
            success:function(response,status,xhr){
                if (xhr.status == 200)
                {
                    // засовываем результат в div с классом search__result
                    searchResult.html(response)
                    searchResult.addClass('border')
                }
            },
            error:function(obj,status,error)
            {
                console.error('Ошибка '+error)
            }
        })
    },delay)
            // сохраняем в глобальную переменную последнее искомое значение
            searchText = searchInput.val()
})

