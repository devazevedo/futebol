(function () {
    const menuToggle = document.querySelector('.menu-toggle')
    menuToggle.onclick = function (e) {
        const body = document.querySelector('body')
        body.classList.toggle('hide-sidebar')
    }
})()

function enabledFilter(e) {
    const btn = document.getElementById('btn-filter');
    if(e.value != '') {
        btn.removeAttribute('disabled');
    } else {
        btn.setAttribute('disabled', true);
    }
}
