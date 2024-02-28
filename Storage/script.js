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



  function togglePasswordVisibility(inputId, toggleIconId) {
    const passwordInput = document.getElementById(inputId);
    const toggleIcon = document.getElementById(toggleIconId);

    if (passwordInput.type === 'password') {
        passwordInput.type = 'text';
        toggleIcon.classList.add('fa-eye');
        toggleIcon.classList.remove('fa-eye-slash');
        passwordInput.focus();
    } else {
        passwordInput.type = 'password';
        toggleIcon.classList.add('fa-eye-slash');
        toggleIcon.classList.remove('fa-eye');
        passwordInput.focus();
    }
}


function validatePhoneNumber(input) {
  // Remove any non-numeric or non-special characters
  input.value = input.value.replace(/[^0-9#+*]/g, '');
}


