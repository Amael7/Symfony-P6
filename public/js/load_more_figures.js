const divParent = document.getElementsByClassName('js-load-more-div');
const btnLoadMore = document.getElementById('js-load-more-btn');
const numberFigureLoaded = document.getElementsByClassName('figure-box').length;

const href = `/${numberFigureLoaded}`;
console.log(href);


btnLoadMore.addEventListener('click', function(){
  fetch(href, {
    method: "GET",
    headers: {
      'X-Requested-With': "XMLHttpRequest",
      'Content-Type': 'application/json'
    }
  }).then(
    // On récupère la réponse en json
    response => response.json()
  ).then(data => {
    if(data) {
      console.log(data);
    } else {
      console.log("error");
    }
  }).catch(e => alert(e))
})
