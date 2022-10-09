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
    case 'INPUT':
    case 'LI':
            // получаем список атрибутов текущего элемента
            // если не submit тогда выходим с этого блока кода
            if (elem.getAttribute('type') != 'submit' && elem.nodeName == 'INPUT') return false
            // нужно проверить, что этот LI находится в блоке filter
            // closest('.filters')- указываем до какого элемента мы всплываем
            if ( filters )
            {
                // получаем текст и заменяем его в элементе с классом filters__name
                if (elem.nodeName != 'INPUT')
                {
                  elem.closest('div').querySelector('.filters__name span').textContent = elem.textContent 
                }
                else
                {
                  // поднялись до родителя
                  let ul = elem.parentNode
                  let val1 = ul.querySelectorAll('input')[0]
                  let val2 = ul.querySelectorAll('input')[1]

                  elem.closest('div').querySelector('.filters__name span').textContent = val1.value +' - '+ val2.value  
                }
            
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