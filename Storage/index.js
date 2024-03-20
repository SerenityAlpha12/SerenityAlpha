
function submitForm(event) {
    event.preventDefault(); // Prevent the default form submission
    
    // Hide the form - Commented out to prevent form disappearance
    // document.getElementById('contactForm').style.display = 'none';
    
    // Fetch the form data
    const formData = new FormData(document.getElementById('contactForm'));
    
    // Send the form data via AJAX
    fetch('clientmessage.php', { // Change the URL to clientmessage.php
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
        // Show the form again after a short delay
        setTimeout(function() {
            document.getElementById('contactForm').style.display = 'block';
            // Clear form fields
            document.getElementById('contactForm').reset();
            // Handle the successful response here
            console.log('Form submitted successfully:', data);
            document.getElementById('alertMessage').innerHTML = '<div class="success">Message sent successfully.</div>';
            // Optionally, display a success message or perform other actions
        }, 2000); // Delay of 2000 milliseconds before showing the form again
    })
    .catch(error => {
        // Show the form again after a short delay
        setTimeout(function() {
            document.getElementById('contactForm').style.display = 'block';
            // Handle errors here
            console.error('There was a problem with the form submission:', error);
            document.getElementById('alertMessage').innerHTML = '<div class="error">Error: ' + error.message + '</div>';
            // Optionally, display an error message or perform other actions
        }, 2000); // Delay of 2000 milliseconds before showing the form again
    });
}