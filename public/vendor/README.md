# Bibliothèques tierces hébergées

Ces fichiers sont servis depuis le dépôt (pas de CDN) pour la fiabilité, la rapidité et le respect de la vie privée.

| Bibliothèque | Version | Licence | Dossier |
|---|---|---|---|
| jQuery | 3.6.0 | MIT | `jquery/` |
| jQuery UI | 1.14.1 | MIT | `jquery-ui/` |
| DataTables | 1.13.6 | MIT | `datatables/` |
| FullCalendar (fiche d'un bien) | 6.1.11 | MIT | `fullcalendar/` |
| FullCalendar (calendrier propriétaire) | 5.11.3 | MIT | `fullcalendar5/` |
| Chart.js | 4.4.0 | MIT | `chartjs/` |

Mapbox GL JS (carte) reste chargé depuis le CDN de Mapbox : il nécessite de toute façon leur service de tuiles et un jeton.

Pour mettre à jour une bibliothèque : l'installer avec `npm`, copier le fichier dans le dossier concerné et mettre à jour ce tableau.
