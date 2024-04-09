// Wait for the DOM to be fully loaded before executing any JavaScript
document.addEventListener("DOMContentLoaded", function() {
    // Get the registration form element
    const form = document.querySelector('form');

    // Add event listener for form submission
    form.addEventListener('submit', function(event) {
        // Prevent the default form submission behavior
        event.preventDefault();

        // Serialize the form data into a query string
        const formData = new FormData(form);
        console.log(formData);

        // Perform additional client-side validation if needed
        // For example, you could check if required fields are filled, etc.

        // Send the form data to the server using fetch API
        // fetch('process_registration.php', {
        //     method: 'POST',
        //     body: formData
        // })
        // .then(response => {
        //     // Check if the response is ok (status code 200)
        //     if (response.ok) {
        //         // Display success message to the user
        //         alert('Registration successful!');

        //         // Optionally, redirect the user to a different page
        //         // window.location.href = 'success.html';
        //     } else {
        //         // Display error message to the user
        //         alert('Registration failed. Please try again later.');
        //     }
        // })
        // .catch(error => {
        //     console.error('Error:', error);
        //     // Display error message to the user
        //     alert('An unexpected error occurred. Please try again later.');
        // });
    });
});
