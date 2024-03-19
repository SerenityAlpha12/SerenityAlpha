
// Get the modal
var modal = document.getElementById("appointmentModal");

// Get the <span> element that closes the modal
var span = document.getElementsByClassName("close")[0];

// When the user clicks the button, open the modal
function openApptModal() {
  modal.style.display = "block";
}

// Function to clear the alert message
function clearAlertMessage() {
  document.getElementById('alertMessage').innerHTML = '';
}

// Function to close the modal and clear the alert message
function closeApptModal() {
  document.getElementById('appointmentModal').style.display = 'none';
  clearAlertMessage(); // Clear the alert message
}

// Event listener for the modal close button
document.querySelector('.modal-content .close').addEventListener('click', function() {
  closeApptModal();
});

// When the user clicks anywhere outside of the modal, close it
window.onclick = function(event) {
  if (event.target == modal) {
    modal.style.display = "none";
  }
}





function submitForm(event) {
  event.preventDefault(); // Prevent the default form submission
  
  // Hide the form
  document.getElementById('appointmentForm').style.display = 'none';

  // Show loading screen after a short delay
  setTimeout(function() {
      document.getElementById('loadingScreen').style.display = 'block';
  }, 300); // Delay of 300 milliseconds before showing the loader
  
  // Fetch the form data
  const formData = new FormData(document.getElementById('appointmentForm'));
  
  // Send the form data via AJAX
  fetch('userappointments.php', {
      method: 'POST',
      body: formData
  })
  .then(response => {
      if (response.ok) {
          return response.text(); // Return the response body as text
      }
      throw new Error('Network response was not ok.');
  })
  .then(data => {
      // Hide loading screen after 3 seconds
      setTimeout(function() {
          document.getElementById('loadingScreen').style.display = 'none';
          // Show the form again
          document.getElementById('appointmentForm').style.display = 'block';
          // Clear form fields
          document.getElementById('appointmentForm').reset();
          // Handle the successful response here
          console.log('Form submitted successfully:', data);
          document.getElementById('alertMessage').innerHTML = '<div class="alert alert-success">Appointment created successfully.</div>';
          // Optionally, display a success message or perform other actions
      }, 3000);
  })
  .catch(error => {
      // Hide loading screen after 3 seconds
      setTimeout(function() {
          document.getElementById('loadingScreen').style.display = 'none';
          // Show the form again
          document.getElementById('appointmentForm').style.display = 'block';
          // Handle errors here
          console.error('There was a problem with the form submission:', error);
          document.getElementById('alertMessage').innerHTML = '<div class="alert alert-danger">Error: ' + error.message + '</div>';
          // Optionally, display an error message or perform other actions
      }, 3000);
  });
}
