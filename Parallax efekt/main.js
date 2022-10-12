let snow_cap = document.getElementById("cap");

window.addEventListener('scroll', function() {
    var value = window.scrollY;

    cap.style.left = value * 1 + 'px';
})