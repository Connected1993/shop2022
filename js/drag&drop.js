const zone = document.querySelector(".drag-zone");
//https://developer.mozilla.org/en-US/docs/Web/API/DragEvent
// масимальный размер файла по умолчанию Mb
const defaultSize = 10 * 1024;
// получили DOM элемент drag-preview
const zonePreview = document.querySelector(".drag-preview");
// допустимые расширения файлов
const accessExt = {
  png: defaultSize,
  jpg: defaultSize,
  jpeg: defaultSize,
  zip: 20 * 1024,
};

let templateFiles = {};

let counter = 0;

// перечисляем форматы изображений из которых можно сделать preview
const images = ["jpg", "jpeg", "webp", "png", "svg"];

zone.addEventListener("dragenter", (event) => {
  console.log("drag enter");
});

zone.addEventListener("dragleave", (event) => {
  zone.style.backgroundColor = "initial";
});

zone.addEventListener("dragover", (event) => {
  event.preventDefault();
  console.log("dragover");
});

zone.addEventListener("drop", (event) => {
  // отмена стандартного поведения
  event.preventDefault();

  zone.style.backgroundColor = "initial";

  // очистка
  //   zonePreview.innerHTML = "";
  // получаем список файлов
  files = event.dataTransfer.files;
  console.log(files);
  // проверка на наличие файлов
  if (files.length) {
    // перебираем список с файлами
    for (let file in files) {
      // записываем номер индекса в переменную
      let index = file;
      // записали обьект в переменную
      file = files[file];
      // проверяем, что переменная file == oject
      if (typeof file != "object") continue;
      // получили имя файла
      // получили последний элемент массива
      let ext = file.name.split(".").pop();
      // если допустимое расширение файла тогда
      // проверяем его размер
      if (accessExt[ext] && file.size / 1024 <= accessExt[ext]) {
        //записываем фаил во временный объект
        templateFiles[++counter] = file;
        // поиск по массиву images и возвращает true/false если такое значение есть
        if (images.includes(ext)) {
          // генирируем url для картинки
          let url = URL.createObjectURL(file);
          zonePreview.insertAdjacentHTML(
            "afterbegin",
            `
                    <div class="preview__item" file-index="${counter}">
                        <i class="close fa fa-2x fa-times-circle-o"></i>
                        <img class="preview-img" src="${url}">
                    </div>
                    `
          );
          //zonePreview.insertAdjacentHTML('afterbegin','<img src="'+url+'">')
        } else {
          zonePreview.insertAdjacentHTML(
            "afterbegin",
            `
                    <div class="preview__item" file-index="${counter}">
                        <i class="close fa fa-2x fa-times-circle-o"></i>
                        <i class="fa fa-4x fa-file-o" aria-hidden="true"></i>
                    </div>
                    `
          );
        }
      }
    }
  }
});

// событе на блок zonePreview
zonePreview.addEventListener("click", (event) => {
  // элемент по которому был клик
  let elem = event.target;

  // classList - возвращает список всех  классов у данного элемента
  // contains - проверят наличие класса в массиве classList
  // таким образом мы понимаем, что был нажат крестик
  if (elem.classList.contains("close")) {
    // получаем родителя элемента parentNode
    // getAttribute('file-index') - получаем значение атрибута
    let index = elem.parentNode.getAttribute("file-index");
    delete templateFiles[index];
    --counter;

    elem.parentNode.remove();
  }
});

// по нажатию на область дроп зоны, имитируем клик 
// по input setFiles
zone.addEventListener('click',event=>{
  setFiles.click()
})


// функция отправки файла
function uploadFiles(idProduct = 0) {

  // если нет ни одного файла тогда прерываем загрузку
  if (Object.keys(templateFiles).length == 0) {
    alert('Выберите изображение!')
    return false;
  }

  if (!idProduct){
   alert('Не известный idProduct ='+idProduct)
   return false
  }

  zonePreview.innerHTML = ''

  zonePreview.insertAdjacentHTML('afterbegin','<i class="fa fa-cog fa-spin fa-5x fa-fw"></i>')

  // создаем свою коллекцию файлов
  let filesList = new FormData();

  filesList.append("action", "uploadFiles");
  filesList.append("idProduct",idProduct)
  //ПЕРЕБИРАЕМ ОБЪЕКТ С ФАЙЛАМИ

  for (let index in templateFiles) 
  {
    filesList.append(index, templateFiles[index]);
  }

  $.ajax({
    url: "core/handler.php",
    method: "POST",
    cache: false,
    processData: false,
    contentType: false,
    data: filesList,
    success: function (response, status, xhr) {
      if (xhr.status == 200 && response) {
        console.log(response);
        zonePreview.innerHTML = '<p>файлы усешно загружены!</p>';
        templateFiles = {}
        counter = 0
        return response
      }
    },
    error: function (obj, status, error) {
      console.error("Ошибка " + error);
      return false
    },
  });

};
