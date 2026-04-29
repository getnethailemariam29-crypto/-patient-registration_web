/**
 * Debre Berhan University Student Portal
 * Main JavaScript File
 */

document.addEventListener('DOMContentLoaded', function() {
    // Mobile Navigation Toggle
    const navToggle = document.getElementById('navToggle');
    const navMenu = document.getElementById('navMenu');
    
    if (navToggle && navMenu) {
        navToggle.addEventListener('click', function() {
            navMenu.classList.toggle('active');
        });
        
        // Close menu when clicking outside
        document.addEventListener('click', function(e) {
            if (!navToggle.contains(e.target) && !navMenu.contains(e.target)) {
                navMenu.classList.remove('active');
            }
        });
    }
    
    // Auto-hide alerts after 5 seconds
    const alerts = document.querySelectorAll('.alert');
    alerts.forEach(function(alert) {
        setTimeout(function() {
            alert.style.transition = 'opacity 0.5s ease, transform 0.5s ease';
            alert.style.opacity = '0';
            alert.style.transform = 'translateY(-10px)';
            setTimeout(function() {
                alert.remove();
            }, 500);
        }, 5000);
    });
    
    // Highlight active nav link
    const currentPath = window.location.pathname;
    const navLinks = document.querySelectorAll('.nav-link');
    navLinks.forEach(function(link) {
        if (link.getAttribute('href') === currentPath) {
            link.style.background = 'rgba(255, 255, 255, 0.2)';
            link.style.color = '#ffffff';
        }
    });
    
    // Table row hover effect
    const tableRows = document.querySelectorAll('.data-table tbody tr');
    tableRows.forEach(function(row) {
        row.addEventListener('mouseenter', function() {
            this.style.transition = 'background 0.2s ease';
        });
    });
    
    // Form validation enhancement
    const forms = document.querySelectorAll('form');
    forms.forEach(function(form) {
        form.addEventListener('submit', function(e) {
            const requiredInputs = form.querySelectorAll('[required]');
            let isValid = true;
            
            requiredInputs.forEach(function(input) {
                if (!input.value.trim()) {
                    isValid = false;
                    input.style.borderColor = '#e74c3c';
                    input.addEventListener('input', function() {
                        this.style.borderColor = '';
                    }, { once: true });
                }
            });
            
            if (!isValid) {
                e.preventDefault();
                showNotification('Please fill in all required fields.', 'error');
            }
        });
    });
    
    // Grade input auto-calculation
    const gradeInputs = document.querySelectorAll('.grade-input');
    gradeInputs.forEach(function(input) {
        input.addEventListener('input', function() {
            const row = this.closest('tr');
            if (row) {
                const inputs = row.querySelectorAll('.grade-input');
                let total = 0;
                inputs.forEach(function(inp) {
                    total += parseFloat(inp.value) || 0;
                });
                // The total is displayed on form submit via PHP
            }
        });
    });
    
    // Smooth scroll for anchor links
    document.querySelectorAll('a[href^="#"]').forEach(function(anchor) {
        anchor.addEventListener('click', function(e) {
            e.preventDefault();
            const target = document.querySelector(this.getAttribute('href'));
            if (target) {
                target.scrollIntoView({ behavior: 'smooth' });
            }
        });
    });
    
    // Add loading state to submit buttons
    const submitButtons = document.querySelectorAll('button[type="submit"]');
    submitButtons.forEach(function(button) {
        button.closest('form').addEventListener('submit', function() {
            button.disabled = true;
            const originalText = button.innerHTML;
            button.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Processing...';
            
            // Re-enable after 5 seconds in case of error
            setTimeout(function() {
                button.disabled = false;
                button.innerHTML = originalText;
            }, 5000);
        });
    });
});

/**
 * Toggle modal visibility
 */
function toggleModal(modalId) {
    const modal = document.getElementById(modalId);
    if (modal) {
        modal.classList.toggle('active');
        
        if (modal.classList.contains('active')) {
            document.body.style.overflow = 'hidden';
            // Focus first input
            setTimeout(function() {
                const firstInput = modal.querySelector('input:not([type="hidden"]), select, textarea');
                if (firstInput) firstInput.focus();
            }, 300);
        } else {
            document.body.style.overflow = '';
        }
    }
}

/**
 * Show notification toast
 */
function showNotification(message, type) {
    type = type || 'info';
    const notification = document.createElement('div');
    notification.className = 'alert alert-' + (type === 'error' ? 'danger' : type);
    notification.innerHTML = '<i class="fas fa-' + (type === 'error' ? 'exclamation-circle' : 'info-circle') + '"></i> ' + message;
    notification.style.position = 'fixed';
    notification.style.top = '20px';
    notification.style.right = '20px';
    notification.style.zIndex = '9999';
    notification.style.minWidth = '300px';
    notification.style.boxShadow = '0 5px 25px rgba(0,0,0,0.2)';
    
    document.body.appendChild(notification);
    
    setTimeout(function() {
        notification.style.transition = 'opacity 0.5s ease';
        notification.style.opacity = '0';
        setTimeout(function() {
            notification.remove();
        }, 500);
    }, 3000);
}

/**
 * Confirm delete action
 */
function confirmDelete(message) {
    return confirm(message || 'Are you sure you want to delete this item?');
}

/**
 * Print page content
 */
function printPage() {
    window.print();
}
