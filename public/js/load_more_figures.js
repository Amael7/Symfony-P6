function loadMoreFigures() {
  const btnLoadMore = document.getElementById('js-load-more-btn-figures');

  btnLoadMore.addEventListener('click', function() {
    const divFiguresList = document.getElementById('figure-list');
    const numberFigureLoaded = document.getElementsByClassName('figure-box').length;
    const href = `/figures/${numberFigureLoaded}`;
    console.log(href);

    axios.get(href).then(function(response) {
      divFiguresList.insertAdjacentHTML('beforeend', response.data);
    }).catch(e => alert(e))
  });
}

loadMoreFigures();