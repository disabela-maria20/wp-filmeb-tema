const Menu = () => {
  const menu_mobile = document.querySelector('.menu_burguer')
  const nav_menu = document.querySelectorAll('.menu')

  menu_mobile.addEventListener('click', () => {
    menu_mobile.classList.toggle('active')
    nav_menu.forEach((item) => {
      item.classList.toggle('active')
    })
  })
}

Menu()

const isMobile = () => {
  const userAgent = typeof window.navigator === 'undefined' ? '' : navigator.userAgent
  const isMobileDevice =
    /Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini|Mobile|mobile|CriOS/i.test(
      userAgent
    )
  return isMobileDevice
}

jQuery(document).ready(function ($) {
  $('.slide').owlCarousel({
    loop: true,
    margin: 10,
    nav: true,
    dots: true,
    mouseDrag: true,
    autoplay: true,
    autoplayTimeout: 3000,
    navText : ["<i class='bi bi-chevron-left'></i>","<i class='bi bi-chevron-right'></i>"],
    responsive: {
      0: {
        items: 1
      },
    }
  });
});