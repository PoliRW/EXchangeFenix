const sipka = document.querySelector('.nahoru');
sipka.addEventListener('click', (udalost) => {
    //nastaveni kdese vratime po kliknuti na sipku, smooth nastavemi dela navrat animovani
    window.scrollTo({
        left: 0,
        top: 0,
        behavior: 'smooth'
    })
})
//nastavit kde se sipka objevi
window.addEventListener("scroll", (udalost) => {


    if (window.scrollY > 500) {
        sipka.classList.add("zobrazit");
    }
    else {
        sipka.classList.remove("zobrazit");
    }
});

//zobrazit adress
const adress = document.querySelector('.adress');
const zobrazitAdress = document.querySelectorAll('.zobrazitAdress');

adress.addEventListener('click', (u) => {
    for (const ulice of zobrazitAdress) {
        ulice.className = "zobrazitAdress"
        setTimeout(() => {
            ulice.className = "schovat"
        }, 2000);
    }

})
