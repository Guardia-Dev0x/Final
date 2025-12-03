<!doctype html>
<html lang="fr">
<head>
<meta charset="utf-8" />
<meta name="viewport" content="width=device-width,initial-scale=1" />
<title>Météo - Semaine</title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Bebas+Neue&family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="./../Style/meto.css"/>

</head>
<body>
<header class="header" role="banner">
        <a href="#" class="logo">Campus Meteo</a>

        <nav id="primaryNav" class="nav" role="navigation">
            <ul class="nav-list">
                <li><a href="#accueil">Accueil</a></li>
                <li><a href="evenement.php">Événements</a></li>
                <li><a href="contact.php">Contact</a></li>
                <li><a href="login.php" class="btn btn-secondary">Connexion</a></li>
                <li ><a href="register.php" class="btn btn-primary">Inscription</a></li>
                <li ><a href="meteo.php" class="btn btn-outline">Météo</a></li>
            </ul>
        </nav>
</header>

<div class="wrap">
    <section class="promo card">
    <div class="brand">
        <div>
        <h1>Météo - Semaine</h1>
        <div class="lead">Consultez la température et le temps pour la semaine d'une ville précise.</div>
        </div>
    </div>

    <div class="features">
        <div class="feature"><div class="dot"></div> Prévisions 7 jours</div>
        <div class="feature"><div class="dot"></div> Recherche par ville (géocodage intégré)</div>
        <div class="feature"><div class="dot"></div> API gratuite (Open-Meteo)</div>
    </div>

    <div style="margin-top:18px" id="intro-note" class="muted-note">
        Entrez le nom d'une ville (ex : Paris, Marseille, Lyon) puis cliquez sur Rechercher.
    </div>

    <div class="footer-note">Données fournies par Open-Meteo (gratuit, sans clé).</div>
    </section>

    <aside class="card">
    <div class="weather-header">
        <div class="city-info">
        <div style="display:flex; gap:10px; align-items:center;">
            <input id="cityInput" class="search-small" type="text" placeholder="Ex : Paris" />
            <button id="searchBtn" class="btn">Rechercher</button>
        </div>
        <div id="status" class="muted-note"></div>
        </div>
    </div>

    <div id="currentBox" style="display:none" class="current">
        <div>
        <div id="currentTemp" class="temp">--°C</div>
        <div id="currentDesc" class="meta">--</div>
        </div>
        <div style="margin-left:auto; text-align:right;">
        <div id="placeName" style="font-weight:700">Ville</div>
        <div id="coords" class="muted-note">lat / lon</div>
        </div>
    </div>

    <div id="error" class="error" style="display:none"></div>
    <div id="loader" class="loader" style="display:none">Chargement…</div>

    <div id="forecastWrap" style="margin-top:16px; display:none;">
        <h2 style="font-size:18px; margin:0 0 10px 0;">Prévision 7 jours</h2>
        <div id="forecast" class="forecast-grid"></div>
    </div>

    </aside>
</div>

<script src="./../script/meteo.js"></script>
</body>
</html>