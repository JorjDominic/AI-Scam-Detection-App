// ScamGuard Frontend JavaScript

// Form validation helper
function validateEmail(email) {
    const re = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    return re.test(email);
}

// Initialize on page load
document.addEventListener('DOMContentLoaded', function() {
    initializeEventListeners();
    initializeTooltips();
});

// Event listeners setup
function initializeEventListeners() {
    // Form validation on login/register
    const forms = document.querySelectorAll('form');
    forms.forEach(form => {
        form.addEventListener('submit', function(e) {
            if (!validateForm(this)) {
                e.preventDefault();
            }
        });
    });

    // Risk score animation
    animateRiskScores();

    // Table row interactions
    setupTableInteractions();
}

// Validate form inputs
function validateForm(form) {
    const inputs = form.querySelectorAll('input[required], textarea[required], select[required]');
    let isValid = true;

    inputs.forEach(input => {
        if (input.value.trim() === '') {
            input.style.borderColor = '#dc2626';
            isValid = false;
        } else if (input.type === 'email' && !validateEmail(input.value)) {
            input.style.borderColor = '#dc2626';
            isValid = false;
        } else {
            input.style.borderColor = '';
        }
    });

    return isValid;
}

// Animate risk score numbers
function animateRiskScores() {
    const riskScores = document.querySelectorAll('.risk-score, .stat-number');
    
    riskScores.forEach(element => {
        const finalValue = parseInt(element.textContent);
        if (!isNaN(finalValue)) {
            let currentValue = 0;
            const increment = Math.ceil(finalValue / 50);
            const interval = setInterval(() => {
                currentValue += increment;
                if (currentValue >= finalValue) {
                    element.textContent = finalValue;
                    clearInterval(interval);
                } else {
                    element.textContent = currentValue;
                }
            }, 20);
        }
    });
}

// Setup table row click interactions
function setupTableInteractions() {
    const tableRows = document.querySelectorAll('.scan-table tbody tr');
    
    tableRows.forEach(row => {
        row.addEventListener('mouseenter', function() {
            this.style.backgroundColor = '#f3f4f6';
        });
        
        row.addEventListener('mouseleave', function() {
            this.style.backgroundColor = '';
        });
    });
}

// Initialize tooltips for icons
function initializeTooltips() {
    const indicatorIcons = document.querySelectorAll('.indicator-icon');
    
    indicatorIcons.forEach(icon => {
        const tooltipText = icon.textContent;
        icon.title = getTooltipText(tooltipText);
    });
}

// Get tooltip text based on icon
function getTooltipText(icon) {
    const tooltips = {
        '⚠️': 'Warning indicator',
        '🚩': 'Suspicious pattern',
        '🚨': 'Alert - High risk behavior'
    };
    return tooltips[icon] || '';
}

// Utility: Show notification
function showNotification(message, type = 'info') {
    const notification = document.createElement('div');
    notification.className = `alert alert-${type}`;
    notification.style.position = 'fixed';
    notification.style.top = '20px';
    notification.style.right = '20px';
    notification.style.zIndex = '9999';
    notification.style.minWidth = '300px';
    notification.textContent = message;
    
    document.body.appendChild(notification);
    
    setTimeout(() => {
        notification.remove();
    }, 3000);
}

// Copy to clipboard utility
function copyToClipboard(text) {
    navigator.clipboard.writeText(text).then(() => {
        showNotification('Copied to clipboard!', 'success');
    }).catch(() => {
        showNotification('Failed to copy', 'error');
    });
}

// Format date utility
function formatDate(dateString) {
    const options = { year: 'numeric', month: 'short', day: 'numeric', hour: '2-digit', minute: '2-digit' };
    return new Date(dateString).toLocaleDateString('en-US', options);
}

// Search/filter table rows
function filterTable(tableId, searchInput) {
    const table = document.getElementById(tableId);
    if (!table) return;
    
    const rows = table.querySelectorAll('tbody tr');
    const query = searchInput.toLowerCase();
    
    rows.forEach(row => {
        const text = row.textContent.toLowerCase();
        row.style.display = text.includes(query) ? '' : 'none';
    });
}

// Handle smooth scrolling for anchor links
document.querySelectorAll('a[href^="#"]').forEach(anchor => {
    anchor.addEventListener('click', function(e) {
        const href = this.getAttribute('href');
        if (href !== '#') {
            e.preventDefault();
            const target = document.querySelector(href);
            if (target) {
                target.scrollIntoView({ behavior: 'smooth' });
            }
        }
    });
});

// Keyboard shortcuts
document.addEventListener('keydown', function(e) {
    // Escape key to close modals/alerts
    if (e.key === 'Escape') {
        const alerts = document.querySelectorAll('.alert');
        alerts.forEach(alert => {
            if (alert.style.position === 'fixed') {
                alert.remove();
            }
        });
    }
    
    // Ctrl/Cmd + K for search (can be implemented with actual search feature)
    if ((e.ctrlKey || e.metaKey) && e.key === 'k') {
        e.preventDefault();
        console.log('Search feature would activate here');
    }
});

// Prevent form double submission
document.querySelectorAll('form').forEach(form => {
    form.addEventListener('submit', function() {
        const submitBtn = this.querySelector('button[type="submit"]');
        if (submitBtn) {
            submitBtn.disabled = true;
            submitBtn.style.opacity = '0.6';
            submitBtn.style.cursor = 'not-allowed';
        }
    });
});

// Add active state to navigation links
function setActiveNavLink() {
    const currentPage = window.location.pathname.split('/').pop() || 'index.php';
    const navLinks = document.querySelectorAll('.nav-links a');
    
    navLinks.forEach(link => {
        const href = link.getAttribute('href');
        if (href === currentPage || 
            (currentPage === '' && href === 'index.php') ||
            (currentPage === '/' && href === 'index.php')) {
            link.style.fontWeight = 'bold';
            link.style.color = 'white';
        }
    });
}


setActiveNavLink();

// Console message for development
console.log('ScamGuard Frontend Initialized');
