<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width,initial-scale=1" />
  <title>Planning - Campus Events</title>

  <!-- fonts / global -->
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="./../Style/styles.css"/>

  <!-- FullCalendar CSS -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.11/main.min.css"/>

  <!-- page CSS -->
  <link rel="stylesheet" href="./../Style/evenement.css"/>

  <!-- FullCalendar JS (global bundle) -->
  <script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.11/index.global.min.js"></script>
</head>
<body>
  <header class="header" role="banner">
    <a href="index.html#accueil" class="logo">Campus Events</a>

    <button class="nav-toggle" id="navToggle" aria-controls="primaryNav" aria-expanded="false" aria-label="Afficher le menu">
      <span class="bar"></span><span class="bar"></span><span class="bar"></span>
    </button>

    <nav id="primaryNav" class="nav" role="navigation">
      <ul class="nav-list">
        <li><a href="index.php#accueil">Accueil</a></li>
        <li><a href="evenement.php" class="active">Événements</a></li>
        <li><a href="login.php" class="btn btn-secondary">Connexion</a></li>
        <li><a href="meteo.php" class="btn btn-outline">Météo</a></li>
      </ul>
    </nav>
  </header>

  <main class="page-grid">
    <aside class="panel" aria-labelledby="create-title">
      <h2 id="create-title">Créer un événement</h2>

      <form id="eventForm" class="event-form" autocomplete="off" method="POST">
        <label for="title">Titre</label>
        <input id="title" name="title" required placeholder="Titre de l'événement" />

        <div class="form-row">
          <div>
            <label for="date">Date</label>
            <input id="date" name="date" type="date" required />
          </div>
          <div>
            <label for="start">Heure début</label>
            <input id="start" name="start" type="time" required />
          </div>
        </div>

        <div class="form-row">
          <div>
            <label for="end">Heure fin</label>
            <input id="end" name="end" type="time" />
          </div>
          <div>
            <label for="color">Couleur</label>
            <input id="color" name="color" type="color" value="#ff6b3d" />
          </div>
        </div>

        <label for="desc">Description (optionnel)</label>
        <textarea id="desc" name="desc" rows="4" placeholder="Détails, lieu, lien..."></textarea>

        <div class="form-actions">
          <button class="btn btn-primary" type="submit">Ajouter</button>
          <button id="clearBtn" type="button" class="btn btn-secondary">Effacer</button>
        </div>

        <p class="muted">Les événements sont stockés localement (localStorage) et s'affichent dans la vue semaine.</p>
      </form>

      <hr/>

      <h3>Liste rapide - semaine sélectionnée</h3>
      <div id="weekList" class="week-list" aria-live="polite"></div>
    </aside>

    <section class="calendar-wrap" aria-labelledby="planning-title">
      <h2 id="planning-title" class="section-title">📋 Planning Hebdomadaire</h2>
      <div id="calendar"></div>
    </section>
  </main>

  <footer class="footer" role="contentinfo">
    <p>&copy; 2025 Campus Events</p>
  </footer>

  <script>
  (function(){
    // mobile nav toggle
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

  <script>
  (function(){
    const STORAGE_KEY = 'campus_events_v1';

    function loadEvents(){
      try{
        const raw = localStorage.getItem(STORAGE_KEY);
        return raw ? JSON.parse(raw) : [];
      }catch(e){ return []; }
    }
    function saveEvents(events){
      localStorage.setItem(STORAGE_KEY, JSON.stringify(events));
    }

    function toISO(dateStr, timeStr){
      if(!timeStr) timeStr = '00:00';
      return dateStr + 'T' + timeStr;
    }

    // init calendar
    document.addEventListener('DOMContentLoaded', function() {
      const stored = loadEvents();

      const calendarEl = document.getElementById('calendar');
      const calendar = new FullCalendar.Calendar(calendarEl, {
        initialView: 'timeGridWeek',
        headerToolbar: {
          left: 'prev,next today',
          center: 'title',
          right: 'timeGridWeek,dayGridMonth,listWeek'
        },
        selectable: true,
        editable: false,
        navLinks: true,
        nowIndicator: true,
        weekNumbers: false,
        locale: 'fr',
        height: 'auto',
        events: stored.map(e => ({
          id: e.id,
          title: e.title,
          start: e.start,
          end: e.end || null,
          backgroundColor: e.color || undefined,
          borderColor: e.color || undefined,
          extendedProps: { description: e.desc || '' }
        })),
        eventClick: function(info){
          info.jsEvent.preventDefault();
          const ev = info.event;
          const desc = ev.extendedProps.description || '';
          const keep = confirm('Événement : ' + ev.title + '\n' + (desc ? (desc + '\n\n') : '') + 'Voulez-vous supprimer cet événement ?');
          if(keep){
            // remove from calendar and storage
            const id = ev.id;
            ev.remove();
            const list = loadEvents().filter(x => String(x.id) !== String(id));
            saveEvents(list);
            renderWeekList(calendar);
          }
        },
        datesSet: function(){
          renderWeekList(calendar);
        }
      });

      calendar.render();

      // Form handling
      const form = document.getElementById('eventForm');
      const clearBtn = document.getElementById('clearBtn');

      form.addEventListener('submit', (e) => {
        e.preventDefault();
        const title = document.getElementById('title').value.trim();
        const date = document.getElementById('date').value;
        const start = document.getElementById('start').value;
        let end = document.getElementById('end').value;
        const color = document.getElementById('color').value;
        const desc = document.getElementById('desc').value.trim();

        if(!title || !date || !start){
          alert('Remplissez le titre, la date et l\'heure de début.');
          return;
        }

        if(!end){
          // default duration 1h
          const [h,m] = start.split(':').map(Number);
          const d = new Date(date + 'T' + start);
          d.setHours(h + 1, m);
          end = d.toTimeString().slice(0,5);
        }

        const id = Date.now().toString();
        const ev = {
          id,
          title,
          start: toISO(date, start),
          end: toISO(date, end),
          color,
          desc
        };

        // save
        const list = loadEvents();
        list.push(ev);
        saveEvents(list);

        // add to calendar
        calendar.addEvent({
          id: ev.id,
          title: ev.title,
          start: ev.start,
          end: ev.end,
          backgroundColor: ev.color,
          borderColor: ev.color,
          extendedProps: { description: ev.desc }
        });

        // reset form
        form.reset();
        document.getElementById('color').value = '#ff6b3d';
        renderWeekList(calendar);
      });

      clearBtn.addEventListener('click', () => form.reset());

      // Render a compact list of events for the currently visible week
      window.renderWeekList = function(calendarInstance){
        const listWrap = document.getElementById('weekList');
        listWrap.innerHTML = '';
        const view = calendarInstance.view;
        const start = view.activeStart;
        const end = view.activeEnd;

        // gather events from calendar in the week range
        const weekEvents = calendarInstance.getEvents().filter(ev => {
          const s = ev.start;
          return s && s >= start && s < end;
        }).sort((a,b) => a.start - b.start);

        if(weekEvents.length === 0){
          listWrap.innerHTML = '<div class="empty-state">Aucun événement cette semaine.</div>';
          return;
        }

        const ul = document.createElement('ul');
        ul.className = 'compact-list';
        weekEvents.forEach(ev => {
          const li = document.createElement('li');
          const d = new Date(ev.start);
          const time = d.toLocaleTimeString([], {hour:'2-digit', minute:'2-digit'});
          li.innerHTML = '<strong>' + time + '</strong> — <span class="ev-title">' + ev.title + '</span>' +
            (ev.extendedProps.description ? '<div class="small-desc">' + ev.extendedProps.description + '</div>' : '');
          ul.appendChild(li);
        });
        listWrap.appendChild(ul);
      };

      // initial list render
      renderWeekList(calendar);
    });
  })();
  </script>
</body>
</html>


<?php
// register.php
session_start();
require 'db.php'; //On appel le fichier db.php pour faire le lien entre notre base de données et le fichier

if ($_SERVER["REQUEST_METHOD"] == "POST") { // Quand une requête de type "POST"
    $titre = htmlspecialchars($_POST['title']); // Définition de la variable "name"
    $date = htmlspecialchars($_POST['date']); // Définition de la variable "mail"
    $start = htmlspecialchars($_POST['start']); // Définition de la variable "phone"
    $end = htmlspecialchars($_POST['end']); // Définition de la variable "password"
    $color = htmlspecialchars($_POST['color']); 
    $description = htmlspecialchars($_POST['desc']);  

    // 3. Insérer dans la base
    $stmt= $pdo->prepare("INSERT INTO events (titre, date, start, end, color, description) VALUES (?, ?, ?, ?, ?, ?)");
    if ($stmt->execute([$titre, $date, $start, $end, $color, $description])) {
        // Redirection vers la page de login après succès
        exit();
    } else {
        echo "Erreur lors de l'inscription.";
    }
    }

?>
