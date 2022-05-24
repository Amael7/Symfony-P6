function loadMoreFigures() {
  const btnLoadMore = document.getElementById('js-load-more-btn-figures');

  btnLoadMore.addEventListener('click', function() {
    const arrownUp = document.getElementById('js-arrow-up');

    const divFiguresList = document.getElementById('figure-list');
    const numberFigureLoaded = document.getElementsByClassName('figure-box').length;
    const href = `/figures/${numberFigureLoaded}`;


    axios.get(href).then(function(response) {
      divFiguresList.insertAdjacentHTML('beforeend', response.data);
      const numberFigureLoadNow = document.getElementsByClassName('figure-box').length;
      const totalFigures = btnLoadMore.dataset.totalNumberFigures;

      if (numberFigureLoadNow >= 15) {
        arrownUp.classList.remove('d-none');
      } else if (numberFigureLoadNow < 15) {
        arrownUp.classList.add('d-none');
      } 
      if (totalFigures == numberFigureLoadNow) {
        btnLoadMore.classList.add('d-none');
      }
    }).catch(e => alert(e))
  });
}

loadMoreFigures();