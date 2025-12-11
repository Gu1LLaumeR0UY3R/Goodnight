# 🎨 Dossier des Cadres de Profil

Ce dossier contient tous les fichiers PNG des cadres de profil utilisés dans le système Easter Eggs.

## 📁 Structure

```
frames/
├── bronze_frame.png
├── silver_frame.png
├── gold_frame.png
├── rainbow_frame.png
├── neon_frame.png
├── vintage_frame.png
├── crystal_frame.png
├── fire_frame.png
└── ... (autres cadres uploadés)
```

## 📋 Spécifications

- **Format** : PNG avec canal alpha (transparence)
- **Dimensions recommandées** : 512x512 pixels
- **Taille maximale** : 5 MB
- **Nomenclature** : `frame_[timestamp].[extension]` (généré automatiquement)

## 🔒 Permissions

Ce dossier doit être accessible en écriture par le serveur web pour permettre l'upload de nouveaux cadres.

```bash
chmod 755 public/cadre/frames/
```

## ⚠️ Important

- Ne supprimez **JAMAIS** manuellement les fichiers de ce dossier
- Utilisez toujours l'interface d'administration pour supprimer un cadre
- La suppression via l'admin supprime à la fois l'entrée en BDD et le fichier physique

## 🎨 Création de cadres

Pour créer vos propres cadres :

1. **Dimensions** : Créez une image de 512x512 px
2. **Centre transparent** : Le centre doit être transparent pour laisser voir l'avatar
3. **Format** : Exportez en PNG-24 avec transparence
4. **Upload** : Utilisez l'interface admin `/admin/cadres/create`

## 📝 Exemples de cadres

### Cadre simple
```
┌─────────────┐
│  ╔═══════╗  │
│  ║       ║  │  ← Bordure colorée
│  ║ [IMG] ║  │
│  ║       ║  │  ← Centre transparent
│  ╚═══════╝  │
└─────────────┘
```

### Cadre avec effet
```
┌─────────────┐
│  ✨╔═══╗✨  │
│  ║       ║  │  ← Effets décoratifs
│  ║ [IMG] ║  │
│  ║       ║  │  
│  ⭐╚═══╝⭐  │
└─────────────┘
```

---

**Ce dossier est géré automatiquement par le système.**  
**Ne modifiez son contenu que via l'interface d'administration.**
