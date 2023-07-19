function validateForm() {
    const name = document.getElementById('name').value;
    const password = document.getElementById('password').value;
    const phone = document.getElementById('phone').value;
    const address = document.getElementById('address').value;
    const profilePic = document.getElementById('profile_pic').value;
    const userselects = document.querySelector('input[name="userselects"]:checked');
    const email = document.getElementById('email').value;
    const errors = [];
    
    // Check for empty fields
    if (name.trim() === '') {
        errors.push("Name field is required");
    }
    if (email.trim() === '') {
        errors.push("Email field is required");
    }
    if (password.trim() === '') {
        errors.push("Password field is required");
    }
    if (phone.trim() === '') {
        errors.push("Phone field is required");
    }
    if (address.trim() === '') {
        errors.push("Address field is required");
    }
    if (!userselects) {
        errors.push("Please select either Carrier or Consignor");
    }
        
    // Validate email format
    const emailRegex = /^\S+@\S+\.\S+$/;
    if (!emailRegex.test(email)) {
        errors.push("Invalid email format");
    }
        
    // Validate phone number length
    if (phone.length !== 10) {
        errors.push("From JS Contact Number Length must be 10");
    }

    // Validate profile picture file type only if a profile picture is selected
    if (profilePic) {
        const allowedExts = ["jpg", "jpeg", "png", "gif"];
        const ext = profilePic.split('.').pop().toLowerCase();
        if (!allowedExts.includes(ext)) {
            errors.push("From js Only jpg, jpeg, png, and gif files are accepted for the profile picture");
        }
    }

    // Display errors and prevent form submission if there are any errors
    if (errors.length > 0) {
        alert(errors.join("\n"));
        return false;
    }

    // Form is valid, allow form submission
    return true;
}
