// глобальный обработчий событий
document.body.addEventListener('click',function(event){

  // получаем текущ., элемент по которому был произведен клик
  let elem = event.target
  let data = new FormData
  let filters = elem.closest('.filters')
  data.append('action','filters')

  // проверяем имя элемента   
  switch(elem.nodeName)
  {
    case 'LI':
            // нужно проверить, что этот LI находится в блоке filter
            // closest('.filters')- указываем до какого элемента мы всплываем
            if ( filters )
            {
                // получаем текст и заменяем его в элементе с классом filters__name
                elem.closest('div').querySelector('.filters__name span').textContent = elem.textContent 
            
                elem.parentNode.setAttribute( 'data',elem.getAttribute('data-id') )

                // получаем id категории
                let category = filters.querySelector('.filters__category ul').getAttribute('data')
                // получаем id размера
                let size = filters.querySelector('.filters__size ul').getAttribute('data')
                // получаем цену
                let price  = filters.querySelector('.filters__price ul')

                data.append('params',JSON.stringify({
                    c:category,
                    s:size,
                    // от
                    pStart: price.querySelectorAll('input')[0].value,
                    // до
                    pEnd: price.querySelectorAll('input')[1].value,
                }))

                // скрываем текущий фильтр
                // получаем родителя у элемента по которому нажали
                // родитель у нас ul
                elem.parentNode.classList.add('d-none')
                sendRequest(data)
            }
    break;
  }

})


function sendRequest(body)
{
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
              
          }
      },
      error:function(obj,status,error)
      {
          console.error('Ошибка '+error)
      }
    })
}