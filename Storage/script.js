var swiper = new Swiper(".mySwiper", {
    cssMode: true,
    navigation: {
      nextEl: ".swiper-button-next",
      prevEl: ".swiper-button-prev",
    },
    pagination: {
      el: ".swiper-pagination",
    },
    mousewheel: true,
    keyboard: true,
  });


  function toggleAccordion(title) {
    var content = title.nextElementSibling;
    content.style.display = (content.style.display === 'block') ? 'none' : 'block';
  }