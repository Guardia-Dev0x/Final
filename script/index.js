// Petit script pour le menu mobile (léger et accessible)
    (function(){
        const btn = document.getElementById('navToggle');
        const nav = document.getElementById('primaryNav');
        btn.addEventListener('click', ()=> {
            const expanded = btn.getAttribute('aria-expanded') === 'true';
            btn.setAttribute('aria-expanded', String(!expanded));
            nav.classList.toggle('nav-open');
        });
    })();