// common-validation.js
document.addEventListener('DOMContentLoaded', function() {
    function validateName(name) {
        const regex = /^[A-Z][a-z]*( [A-Z][a-z]*)*$/;
        return regex.test(name);
    }
    function validatePhone(phone) {
        const regex = /^\+\d{1,3}[ ]?\d{6,14}$/;
        return regex.test(phone);
    }
    function validateEmail(email) {
        if (!email) return true; 
        const regex = /^[A-Za-z0-9._%+-]+@[A-Za-z0-9.-]+\.[A-Za-z]{2,}$/;
        return regex.test(email);
    }
    function validatePassword(password) {
        // At least 8 chars, one special char
        const regex = /^(?=.*[!@#$%^&*])[A-Za-z\d!@#$%^&*]{8,}$/;
        return regex.test(password);
    }
    // Apply name validation to all name fields
    const nameFields = document.querySelectorAll('input[type="text"][id*="name"], input[type="text"][id*="Name"]');
    nameFields.forEach(field => {
        field.addEventListener('input', function() {
            this.value = this.value.replace(/[^A-Za-z ]/g, '');
            if (this.value.length > 0) {
                this.value = this.value.toLowerCase()
                    .split(' ')
                    .map(word => word.charAt(0).toUpperCase() + word.slice(1))
                    .join(' ');
            }
        });
    });
    // Apply phone validation to all phone fields
    const phoneFields = document.querySelectorAll('input[type="tel"], input[type="text"][id*="phone"], input[type="text"][id*="Phone"]');
    phoneFields.forEach(field => {
        field.addEventListener('input', function() {
            this.value = this.value.replace(/[^0-9+ ]/g, '');
            if (this.value.startsWith('251')) {
                this.value = '+' + this.value;
            }
            const errorElement = document.getElementById(`${this.id}Error`);
            if (errorElement) {
                const isValid = validatePhone(this.value);
                if (!isValid && this.value.length > 0) {
                    errorElement.style.display = 'block';
                    this.classList.add('error');
                    this.classList.remove('success');
                } else {
                    errorElement.style.display = 'none';
                    this.classList.remove('error');
                    if (isValid) this.classList.add('success');
                }
            }
        });
    });
    // Apply email validation to all email fields
    const emailFields = document.querySelectorAll('input[type="email"]');
    emailFields.forEach(field => {
        field.addEventListener('blur', function() {
            const errorElement = document.getElementById(`${this.id}Error`);
            if (errorElement) {
                const isValid = validateEmail(this.value);
                if (!isValid && this.value.length > 0) {
                    errorElement.style.display = 'block';
                    this.classList.add('error');
                    this.classList.remove('success');
                } else {
                    errorElement.style.display = 'none';
                    this.classList.remove('error');
                    if (isValid) this.classList.add('success');
                }
            }
        });
    });
    // Password validation for login and registration forms
    const passwordFields = document.querySelectorAll('input[type="password"]');
    passwordFields.forEach(field => {
        field.addEventListener('input', function() {
            const errorElement = document.getElementById(`${this.id}Error`);
            if (errorElement) {
                const isValid = validatePassword(this.value);
                if (!isValid && this.value.length > 0) {
                    errorElement.style.display = 'block';
                    this.classList.add('error');
                    this.classList.remove('success');
                } else {
                    errorElement.style.display = 'none';
                    this.classList.remove('error');
                    if (isValid) this.classList.add('success');
                }
            }
            // Handle password confirmation if exists
            const confirmPasswordField = document.getElementById('confirmPassword');
            if (confirmPasswordField && this.id === 'password') {
                if (confirmPasswordField.value.length > 0) {
                    if (this.value !== confirmPasswordField.value) {
                        document.getElementById('confirmPasswordError').style.display = 'block';
                        confirmPasswordField.classList.add('error');
                        confirmPasswordField.classList.remove('success');
                    } else {
                        document.getElementById('confirmPasswordError').style.display = 'none';
                        confirmPasswordField.classList.remove('error');
                        confirmPasswordField.classList.add('success');
                    }
                }
            }
        });
    });
    // Form submission handling for all forms
    const forms = document.querySelectorAll('form');
    forms.forEach(form => {
        form.addEventListener('submit', function(e) {
            let isValid = true;
            const requiredFields = form.querySelectorAll('[required]');
            requiredFields.forEach(field => {
                if (!field.value.trim()) {
                    field.classList.add('error');
                    isValid = false;
                    // Show error message if exists
                    const errorElement = document.getElementById(`${field.id}Error`);
                    if (errorElement) {
                        errorElement.textContent = 'This field is required';
                        errorElement.style.display = 'block';
                    }
                }
            });
            if (form.id === 'registrationForm') {
                isValid &= validateField(
                    document.getElementById('firstName'),
                    validateName,
                    document.getElementById('firstNameError'),
                    'First letter must be capital and only letters allowed'
                );
            }
            if (!isValid) {
                e.preventDefault();
                alert('Please correct the errors in the form.');
            }
        });
    });
    // Helper function to validate a field
    function validateField(field, validationFn, errorElement, errorMessage) {
        if (!field || !errorElement) return true;
        const isValid = validationFn(field.value);
        if (!isValid && field.value.length > 0) {
            errorElement.textContent = errorMessage;
            errorElement.style.display = 'block';
            field.classList.add('error');
            field.classList.remove('success');
            return false;
        } else {
            errorElement.style.display = 'none';
            field.classList.remove('error');
            if (isValid) field.classList.add('success');
            return true;
        }
    }
    // Initialize date fields 
    const daySelect = document.getElementById('birthDay');
    const yearSelect = document.getElementById('birthYear');
    if (daySelect && yearSelect) {
        for (let day = 1; day <= 31; day++) {
            const option = document.createElement('option');
            option.value = day;
            option.textContent = day;
            daySelect.appendChild(option);
        }
        // Populate years from current year back to 1900
        const currentYear = new Date().getFullYear();
        for (let year = currentYear; year <= 2050; year++) {
            const option = document.createElement('option');
            option.value = year;
            option.textContent = year;
            yearSelect.appendChild(option);
        }
    }
});