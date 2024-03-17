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


// Get the modal
var modal = document.getElementById("myModal");

// Get the message icon
var messageIcon = document.getElementById("messageIcon");

// Get the <span> element that closes the modal
var span = document.getElementsByClassName("close")[0];

// When the user clicks the message icon, open the modal 
messageIcon.onclick = function() {
  modal.style.display = "block";
}

// When the user clicks on <span> (x), close the modal
span.onclick = function() {
  modal.style.display = "none";
}

// When the user clicks anywhere outside of the modal, close it
window.onclick = function(event) {
  if (event.target == modal) {
    modal.style.display = "none";
  }
}
