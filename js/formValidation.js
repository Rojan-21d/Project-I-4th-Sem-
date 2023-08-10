
// Client-side validation function with SweetAlert integration
function validateForm() {
    var errors = [];
    var name = document.getElementById("name").value;
    var email = document.getElementById("email").value;
    var password = document.getElementById("password").value;
    var phone = document.getElementById("phone").value;
    
    if (name === "") {
        errors.push("Form js: Name is required.");
    }
    
    if (email === "") {
        errors.push("Form js: Email is required.");
    } else if (!validateEmail(email)) {
        errors.push("Form js: Invalid email format.");
    }
    
    if (password === "") {
        errors.push("Form js: Password is required.");
    } else if (password.length < 8 || password.length > 24) {
        errors.push("Form js: Password must be between 8 and 24 characters.");
    }
    
    if (phone === "") {
        errors.push("Form js: Phone number is required.");
    } else if (phone.length !== 10) {
        errors.push("Form js: Phone number must be 10 digits.");
    }
    
    // Display errors using SweetAlert with bullet points
    if (errors.length > 0) {
        var errorMessage = `<div class="error-list">${errors.map(error => `• ${error}`).join("<br>")}</div>`;
        Swal.fire({
            icon: 'error',
            title: 'Sign Up Error',
            html: errorMessage,
            showCloseButton: true,
        });            
        return false;
    }
    
    return true;
}

// Email validation function
function validateEmail(email) {
    var re = /\S+@\S+\.\S+/;
    return re.test(email);
}
