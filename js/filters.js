let FILTER = false


function showFilter(filter)
{

    if (FILTER)
    {

    }

    if (filter.nextElementSibling.classList.contains('d-none'))
    {
        // удаляем  класс для текущего фильтра 
        filter.nextElementSibling.classList.remove('d-none')
        FILTER = true
    }
    else
    {
        // добавляем новый класс для текущего фильтра 
        filter.nextElementSibling.classList.add('d-none')
        FILTER = false
    }
}