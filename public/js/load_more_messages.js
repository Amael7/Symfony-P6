function loadMoreMessages() {
  const btnLoadMore = document.getElementById('js-load-more-btn-messages');

  btnLoadMore.addEventListener('click', function() {
    const divMessagesList = document.getElementById('message-list');
    const figureId = btnLoadMore.dataset.figureId;
    const numberMessagesLoaded = document.getElementsByClassName('message-box').length;
    const href = `/figures/${figureId}/messages/${numberMessagesLoaded}`;
    console.log(href);

    axios.get(href).then(function(response) {
      divMessagesList.insertAdjacentHTML('beforeend', response.data);
    }).catch(e => alert(e))
  });
}

loadMoreMessages();