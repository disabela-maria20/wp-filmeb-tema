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
    dots: false,
    mouseDrag: true,
    autoplay: true,
    autoplayTimeout: 3000,
    navText: ["<i class='bi bi-chevron-left'></i>", "<i class='bi bi-chevron-right'></i>"],
    responsive: {
      0: {
        items: 1
      },
    }
  });
});

jQuery(document).ready(function ($) {
  $('.rapidinhas').owlCarousel({
    loop: true,
    margin: 10,
    nav: false,
    dots: true,
    mouseDrag: true,
    autoplay: false,
    autoplayTimeout: 3000,
    navText: ["<i class='bi bi-chevron-left'></i>", "<i class='bi bi-chevron-right'></i>"],
    responsive: {
      0: {
        items: 1
      },
    }
  });
});

var splide = new Splide('#datas', {
  arrows: true,
  pagination: false,
});
splide.mount();


const cards = document.querySelectorAll('.card');

cards.forEach(card => {
  const cardInfo = card.querySelector('.info');
  card.addEventListener('mousemove', (e) => {
    const rect = card.getBoundingClientRect();
    const mouseX = ((e.clientX - rect.left) / rect.width) * 100;
    const mouseY = ((e.clientY - rect.top) / rect.height) * 100;

    cardInfo.style.position = 'absolute';
    cardInfo.style.left = `${mouseX}%`;
    cardInfo.style.top = `${mouseY}%`;
  });
});



const ordem1Button = document.querySelector('button[aria-label="ordem 1"]');
const ordem2Button = document.querySelector('button[aria-label="ordem 2"]');
const distribuidora = document.querySelector('#distribuidora');

const listaDiv = document.getElementById('lista');
const tabelaDiv = document.getElementById('tabela');
const tableDistribuidora = document.getElementById('tableDistribuidora');

function toggleView(view) {
  if (view === 'lista') {
    listaDiv.style.display = 'block';
    tabelaDiv.style.display = 'none';
    tableDistribuidora.style.display = 'none'
  } else if (view === 'tabela') {
    listaDiv.style.display = 'none';
    tabelaDiv.style.display = 'block';
    tableDistribuidora.style.display = 'none'
  } else if (view === 'tableDistribuidora') {
    listaDiv.style.display = 'none';
    tabelaDiv.style.display = 'none';
    tableDistribuidora.style.display = 'block'
  }
}

ordem1Button.addEventListener('click', () => toggleView('lista'));
ordem2Button.addEventListener('click', () => toggleView('tabela'));
distribuidora.addEventListener('click', () => toggleView('tableDistribuidora'));
toggleView('lista');
