// document.addEventListener : 'resize", à rajouter
document.addEventListener("DOMContentLoaded", function() {
  toggleDisplayMediasBox();

  const mediasBoxDiv = document.getElementById('js-medias-box');
  const btnSeeMedias = document.getElementById('js-btn-see-medias');

  // When we click on the button 'see medias', we toggle the display/hide of the medias
  btnSeeMedias.addEventListener("click", function() {
    toggleDisplayNoneElement(mediasBoxDiv);
  });
});

// Toggle the hiding for the medias-box div and display the button 'see medias'
function toggleDisplayMediasBox() {
  const mediasBoxDiv = document.getElementById('js-medias-box');
  const btnSeeMediaDiv = document.getElementById('js-div-see-medias');

  if (window.innerWidth <= 767) {
    mediasBoxDiv.classList.add('d-none');
    btnSeeMediaDiv.classList.remove('d-none');
  } else {
    btnSeeMediaDiv.classList.add('d-none');
    mediasBoxDiv.classList.remove('d-none');
  }
}

// Check if an element has the "d-none" class and toggle 'd-none' on the element
function toggleDisplayNoneElement(element) {
  if (element.classList.contains('d-none')) {
    element.classList.remove('d-none')
  } else {
    element.classList.add('d-none')
  }
}