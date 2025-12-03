/*
  Météo semaine — utilise Open-Meteo API (https://open-meteo.com/)
  - Recherche de ville via le service de géocodage intégré
  Géocodage : https://geocoding-api.open-meteo.com/v1/search?name=...
  Forecast :  https://api.open-meteo.com/v1/forecast?latitude=...&longitude=...&daily=temperature_2m_max,temperature_2m_min,weathercode&current_weather=true&timezone=auto
*/

const cityInput = document.getElementById('cityInput');
const searchBtn = document.getElementById('searchBtn');
const statusEl = document.getElementById('status');
const loader = document.getElementById('loader');
const errorEl = document.getElementById('error');
const currentBox = document.getElementById('currentBox');
const currentTemp = document.getElementById('currentTemp');
const currentDesc = document.getElementById('currentDesc');
const placeName = document.getElementById('placeName');
const coordsEl = document.getElementById('coords');
const forecastWrap = document.getElementById('forecastWrap');
const forecastEl = document.getElementById('forecast');

function showLoader(show=true){
  loader.style.display = show ? 'block' : 'none';
}
function showError(msg){
  errorEl.textContent = msg;
  errorEl.style.display = msg ? 'block' : 'none';
}

function wcToEmoji(code){
  // basique mapping Open-Meteo weathercode -> emoji + texte
  // source mapping simplifiée
  const map = {
    0: ['☀️','Ciel clair'],
    1: ['🌤️','Principalement ensoleillé'],
    2: ['⛅','Variable'],
    3: ['☁️','Nuageux'],
    45: ['🌫️','Brouillard'],
    48: ['🌫️','Brouillard givrant'],
    51: ['🌦️','Bruine légère'],
    53: ['🌦️','Bruine modérée'],
    55: ['🌦️','Bruine dense'],
    56: ['🌧️','Bruine verglaçante légère'],
    57: ['🌧️','Bruine verglaçante dense'],
    61: ['🌧️','Pluie légère'],
    63: ['🌧️','Pluie modérée'],
    65: ['🌧️','Pluie forte'],
    66: ['🌨️','Pluie verglaçante légère'],
    67: ['🌨️','Pluie verglaçante forte'],
    71: ['❄️','Neige légère'],
    73: ['❄️','Neige modérée'],
    75: ['❄️','Neige forte'],
    77: ['❄️','Grains de glace'],
    80: ['🌧️','Averses légères'],
    81: ['🌧️','Averses'],
    82: ['🌧️','Averses violentes'],
    85: ['❄️','Averses de neige légères'],
    86: ['❄️','Averses de neige fortes'],
    95: ['⛈️','Orage'],
    96: ['⛈️','Orage avec grêle faible'],
    99: ['⛈️','Orage avec grêle forte']
  };
  return map[code] || ['❔','Inconnu'];
}

function formatDay(dateStr){
  const d = new Date(dateStr + 'T00:00:00');
  return d.toLocaleDateString('fr-FR', { weekday: 'short' });
}
function formatDateShort(dateStr){
  const d = new Date(dateStr + 'T00:00:00');
  return d.toLocaleDateString('fr-FR', { day: '2-digit', month: '2-digit' });
}

async function geocode(city){
  const url = `https://geocoding-api.open-meteo.com/v1/search?name=${encodeURIComponent(city)}&count=1&language=fr`;
  const res = await fetch(url);
  if(!res.ok) throw new Error('Erreur géocodage');
  const data = await res.json();
  if(!data.results || data.results.length === 0) return null;
  return data.results[0]; // {name, latitude, longitude, country, timezone}
}

async function fetchForecast(lat, lon){
  const url = `https://api.open-meteo.com/v1/forecast?latitude=${lat}&longitude=${lon}&daily=temperature_2m_max,temperature_2m_min,weathercode&current_weather=true&timezone=auto`;
  const res = await fetch(url);
  if(!res.ok) throw new Error('Erreur API météo');
  return res.json();
}

async function searchCity(){
  const city = cityInput.value.trim();
  if(!city){ showError('Veuillez entrer une ville.'); return; }
  showError('');
  showLoader(true);
  statusEl.textContent = '';
  currentBox.style.display = 'none';
  forecastWrap.style.display = 'none';

  try{
    statusEl.textContent = 'Recherche de la ville…';
    const place = await geocode(city);
    if(!place){
      throw new Error('Ville introuvable.');
    }
    statusEl.textContent = 'Récupération des prévisions…';
    const lat = place.latitude;
    const lon = place.longitude;

    const data = await fetchForecast(lat, lon);
    // current
    if(data.current_weather){
      currentTemp.textContent = Math.round(data.current_weather.temperature) + '°C';
      const [e,txt] = wcToEmoji(data.current_weather.weathercode);
      currentDesc.textContent = `${e} ${txt} • Vent ${Math.round(data.current_weather.windspeed)} km/h`;
      currentBox.style.display = 'flex';
    } else {
      currentBox.style.display = 'none';
    }

    placeName.textContent = [place.name, place.country].filter(Boolean).join(', ');
    coordsEl.textContent = `lat ${lat.toFixed(2)} • lon ${lon.toFixed(2)}`;

    // daily
    const daily = data.daily;
    if(!daily || !daily.time){
      throw new Error('Prévisions indisponibles.');
    }

    // Clear and build forecast cards (7 jours)
    forecastEl.innerHTML = '';
    const count = Math.min(daily.time.length, 7);
    for(let i=0;i<count;i++){
      const day = daily.time[i];
      const max = Math.round(daily.temperature_2m_max[i]);
      const min = Math.round(daily.temperature_2m_min[i]);
      const wc = daily.weathercode[i];
      const [emoji, label] = wcToEmoji(wc);

      const card = document.createElement('div');
      card.className = 'day-card';
      card.innerHTML = `
    <div class="day-name">${formatDay(day)}</div>
    <div class="day-date">${formatDateShort(day)}</div>
    <div class="weather-icon">${emoji}</div>
    <div class="temp-range">${max}° / ${min}°</div>
    <div class="small-muted">${label}</div>
      `;
      forecastEl.appendChild(card);
    }

    forecastWrap.style.display = 'block';
    statusEl.textContent = '';
  }catch(err){
    showError(err.message || 'Erreur inattendue');
  }finally{
    showLoader(false);
  }
}

searchBtn.addEventListener('click', searchCity);
cityInput.addEventListener('keydown', (e)=>{ if(e.key === 'Enter'){ e.preventDefault(); searchCity(); } });

// Optionnel : recherche initiale (décommenter et modifier la ville)
// cityInput.value = 'Paris'; searchCity();