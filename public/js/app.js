// document.addEventListener("DOMContentLoaded", toggleDisplayMediasBox());
document.addEventListener("onResize", function(){
  console.log('Salut');
});

function toggleDisplayMediasBox() {
  console.log(window.innerWidth);

  const mediasBox = document.getElementsByClassName('js-medias-box')[0];
  if (window.innerWidth <= 767) {
    console.log('salut');
    mediasBox.classList.add('d-none');
  } else {
    console.log('salut');
  }
}