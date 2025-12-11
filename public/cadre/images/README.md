# Cadres de Profil (Profile Frames)

Ce dossier contient les images PNG des cadres de profil utilisateur.

## Fichiers attendus

Placez les fichiers PNG suivants dans ce dossier :

1. **gold.png** - Cadre Or Prestigieux 👑
   - Dimensions recommandées : 300x300px (carré ou avec transparence)
   - Taille maximale : 100KB
   - Format : PNG avec transparence

2. **silver.png** - Cadre Argent 🌟
   - Dimensions recommandées : 300x300px
   - Taille maximale : 100KB
   - Format : PNG avec transparence

3. **bronze.png** - Cadre Bronze 🥉
   - Dimensions recommandées : 300x300px
   - Taille maximale : 100KB
   - Format : PNG avec transparence

4. **rainbow.png** - Arc-en-ciel 🌈
   - Dimensions recommandées : 300x300px
   - Taille maximale : 100KB
   - Format : PNG avec transparence

5. **glacier.png** - Glacier Bleu ❄️
   - Dimensions recommandées : 300x300px
   - Taille maximale : 100KB
   - Format : PNG avec transparence

6. **pink.png** - Rose Flamant 🌸
   - Dimensions recommandées : 300x300px
   - Taille maximale : 100KB
   - Format : PNG avec transparence

7. **emerald.png** - Émeraude 💚
   - Dimensions recommandées : 300x300px
   - Taille maximale : 100KB
   - Format : PNG avec transparence

8. **mystique.png** - Violet Mystique 🔮
   - Dimensions recommandées : 300x300px
   - Taille maximale : 100KB
   - Format : PNG avec transparence

## Recommandations

- **Format** : PNG avec transparence (canal alpha)
- **Dimensions** : 300x300 pixels (carré) pour une meilleure compatibilité
- **Taille fichier** : Maximum 100KB par image (recommandé 50KB)
- **Style** : Les images doivent servir de cadre/bordure autour de la photo de profil
  - Utiliser un design de bordure plutôt qu'une image opaque qui couvre tout
  - Prévoir de la transparence au centre pour laisser la photo visible
  - Opacité complète (ou partiellement transparent) sur les bords

## Utilisation

Une fois les fichiers PNG placés dans ce dossier (`/cadre/images/`), les utilisateurs verront :

1. Un message **"Cadres déverrouillés !"** après avoir visité `/cadre/` (easter egg)
2. Une section "Sélectionner un cadre" dans leur profil
3. Un sélecteur avec les 8 cadres disponibles (en plus du cadre par défaut)
4. La possibilité de choisir et d'appliquer un cadre à leur photo de profil

## Notes techniques

- Les chemins des cadres sont stockés dans la base de données (colonne `cadre_profil`)
- Les images PNG sont chargées et affichées comme overlay (z-index: 10)
- Les chemins valides acceptés par le serveur :
  - `null` (pas de cadre / cadre par défaut)
  - `/cadre/images/gold.png`
  - `/cadre/images/silver.png`
  - `/cadre/images/bronze.png`
  - `/cadre/images/rainbow.png`
  - `/cadre/images/glacier.png`
  - `/cadre/images/pink.png`
  - `/cadre/images/emerald.png`
  - `/cadre/images/mystique.png`
