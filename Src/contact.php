<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width,initial-scale=1" />
  <title>Contact - Campus Events</title>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="./../Style/contact.css"/>
  
</head>
<body>
  <header class="header" role="banner">
    <a href="index.html#accueil" class="logo">Campus Events</a>

    <nav id="primaryNav" class="nav" role="navigation">
      <ul class="nav-list">
        <li><a href="index.php#accueil">Accueil</a></li>
        <li><a href="evenement.php">Événements</a></li>
        <li><a href="login.php" class="btn btn-secondary">Connexion</a></li>
        <li ><a href="meteo.php"btn btn-primary">meteo</a></li>
      </ul>
    </nav>
  </header>

  <main class="contact-page">
    <section class="contact-card" aria-labelledby="contact-title">
      <div class="contact-info">
        <h2 id="contact-title">Contactez-nous</h2>
        <p>Pour questions, partenariats ou signalements — notre équipe Campus Events est à votre écoute.</p>
        <a class="contact-email" href="mailto:contact@campusevents.fr">contact@campusevents.fr</a>
        <p style="margin-top:.9rem;color:var(--muted)">Horaires de réponse : lun–ven, 9h–18h</p>
      </div>

      <div class="contact-actions" role="list">
        <a class="social-btn" href="#" aria-label="Instagram Campus Events" role="listitem">
          <!-- simple icône Instagram -->
          <svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
            <rect x="3" y="3" width="18" height="18" rx="5" stroke="currentColor" stroke-width="1.4"/>
            <circle cx="12" cy="12" r="3.1" stroke="currentColor" stroke-width="1.4"/>
            <circle cx="17.5" cy="6.5" r="0.75" fill="currentColor"/>
          </svg>
          @campusevents
        </a>

        <a class="social-btn" href="#" aria-label="Facebook Campus Events" role="listitem">
          <!-- icône Facebook corrigée (viewBox 24x24) -->
          <svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
            <path d="M22 12a10 10 0 1 0-11.5 9.9V14.9h-2v-3h2v-2.2c0-2 1.2-3.1 3-3.1.9 0 1.8.1 1.8.1v2h-1c-1 0-1.3.6-1.3 1.2V12h2.4l-.4 3H14v7.9A10 10 0 0 0 22 12z" fill="currentColor"/>
          </svg>
          /CampusEventsFR
        </a>
      </div>
    </section>

    <section style="margin-top:2.2rem; max-width:900px; margin-left:auto; margin-right:auto;">
      <h3 style="text-align:center; color:var(--text); margin-bottom:.6rem;">Envoyer un message rapide</h3>
      <form style="display:flex; gap:10px; justify-content:center; flex-wrap:wrap;" action="#" method="post" onsubmit="alert('Formulaire simulé — pas d\'envoi.'); return false;">
        <input name="name" placeholder="Votre nom" required style="padding:10px 12px; border-radius:8px; border:1px solid rgba(0,0,0,0.08); min-width:220px;" />
        <input name="email" type="email" placeholder="Votre email" required style="padding:10px 12px; border-radius:8px; border:1px solid rgba(0,0,0,0.08); min-width:220px;" />
        <textarea name="message" placeholder="Message" rows="4" style="width:100%; max-width:760px; padding:12px; border-radius:8px; border:1px solid rgba(0,0,0,0.08);"></textarea>
        <div style="width:100%; display:flex; justify-content:center;">
          <button class="btn btn-primary" type="submit">Envoyer</button>
        </div>
      </form>
    </section>
  </main>

  <footer class="footer" role="contentinfo">
    <p style="margin:6px 0;">&copy; 2025 Campus Events</p>
  </footer>

  <script>
  (function(){
    const btn = document.getElementById('navToggle');
    const nav = document.getElementById('primaryNav');
    if(btn && nav){
      btn.addEventListener('click', ()=> {
        const expanded = btn.getAttribute('aria-expanded') === 'true';
        btn.setAttribute('aria-expanded', String(!expanded));
        nav.classList.toggle('nav-open');
      });
    }
  })();
  </script>
</body>
</html>