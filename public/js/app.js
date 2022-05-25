// Check the window width to update the display of the medias 
function mediasBoxResponsive() {
  const mediasBoxDiv = document.getElementById('js-medias-box');
  const btnSeeMediaDiv = document.getElementById('js-div-see-medias');
  const btnSeeMedias = document.getElementById('js-btn-see-medias');

  if (window.innerWidth <= 992) {
    mediasBoxDiv.classList.add('d-none');
    btnSeeMediaDiv.classList.remove('d-none');
  } else {
    mediasBoxDiv.classList.remove('d-none');
    btnSeeMediaDiv.classList.add('d-none');
  }

  // When we click on the button 'see medias', we toggle the display/hide of the medias
  btnSeeMedias.addEventListener("click", function() {
    mediasBoxDiv.classList.toggle('d-none')
  });
}

// Check the width window and toggle display navbar links 
function navbarResponsive() {
  const navbarLinks = document.getElementById('js-navbar-links');
  const navbarMobile = document.getElementById('js-navbar-mobile');

  if (window.innerWidth <= 992) {
    navbarLinks.classList.add('d-none');
    navbarMobile.classList.remove('d-none');
  } else {
    navbarLinks.classList.remove('d-none');
    navbarMobile.classList.add('d-none');
  }
}

// Toggle display dropdown menu navbar when we click on the hamburger icon menu mobile
function displayDropdownMenuNavbar() {
  const navbarMobile = document.getElementById('js-navbar-mobile');
  const navbarDropdown = document.getElementById('js-menu-dropdown');
  
  navbarMobile.addEventListener('click', function() {
    navbarDropdown.classList.toggle('d-none')
  });
}

  navbarResponsive();
  displayDropdownMenuNavbar();
  if (window.location.pathname.includes("figures")) {
    mediasBoxResponsive();
  }

window.addEventListener("resize", function() {
  navbarResponsive();
  if (window.location.pathname.includes("figures")) {
    mediasBoxResponsive();
  }
});