document.addEventListener('DOMContentLoaded', function() {
    // Highlight active page
    const currentPage = window.location.pathname.split('/').pop() || 'dashboard.html';
    document.querySelectorAll('.nav-icon-link').forEach(link => {
        link.classList.remove('active');
        if (link.href.includes(currentPage)) {
            link.classList.add('active');
        }
    });
    
    // Set user name
    const userName = localStorage.getItem('userName') || 'Trainee';
    document.getElementById('navUserName').textContent = userName;
});