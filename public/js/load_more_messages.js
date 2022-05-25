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
      const numberMessagesLoadNow = document.getElementsByClassName('message-box').length;
      const totalMessages = btnLoadMore.dataset.totalNumberMessages;

      if (totalMessages == numberMessagesLoadNow) {
        btnLoadMore.classList.add('d-none');
      }
    }).catch(e => alert(e))
  });
}

loadMoreMessages();