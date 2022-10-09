const header = document.querySelector('header .menu')

header.addEventListener('click',event=>{
    // получаем элемент по которому был клик
    let elem = event.target
    // если клик был по ссылке внутри блока menu
    // и у текущей ссылке есть атрибут category
    if (elem.nodeName == 'A' && elem.getAttributeNames().includes('category') )
    {
        // отключаем переход по ссылке
        // для того чтобы страничка не перезагружалась
        event.preventDefault()
      
        // скармливаем классу текущий url + атрибут у ссылки
        // запрашиваю результат по ключу p
        // тем самым узнаем категорию
        let getPage = getParams( location.href + elem.getAttribute('href'),'p');

        let data = new FormData

        // наполняем тело POST запроса
        data.append('action','navigation')
        data.append('page', getPage )

        if (getPage == null) return false

        $.ajax({
            url: 'core/handler.php',
            method:'POST',
            cache:false,
            processData:false,
            contentType:false,
            // тело/содержимое post запроса 
            data: data,
            success:function(response,status,xhr){
                if (xhr.status == 200)
                {
                   document.querySelector('main').innerHTML = response
                }
            },
            error:function(obj,status,error)
            {
                console.error('Ошибка '+error)
            }
        })
    }
})

function getParams( url, key )
{
    // получи url и разбили по делиметру
    // pop() получили последний элемент массива , все get параметры
    let params = url.split('?').pop().split('&')
    let result = {}
    params.forEach( string => {
        // например p=man
        let data = string.split('=')
        // записываем в обьект свойство и значение
        // result = {
        //     p:'man'
        // }
        result[ data[0] ] = data[1]
    })
    return result[ key ] ?? null
}