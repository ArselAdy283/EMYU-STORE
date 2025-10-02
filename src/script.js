window.addEventListener('scroll', function () {
    const navbar = document.getElementById('navbar');
    if (window.scrollY > 50) {
        navbar.classList.add('bg-black/30', 'backdrop-blur-md', 'shadow-lg');
        navbar.classList.remove('bg-transparent');
    } else {
        navbar.classList.remove('bg-black/30', 'backdrop-blur-md', 'shadow-lg');
        navbar.classList.add('bg-transparent');
    }
});
