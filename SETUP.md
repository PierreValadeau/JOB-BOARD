# Configuration de l'environnement local

## ⚠️ IMPORTANT - Configuration de la base de données

Chaque développeur doit créer son propre fichier `.env` local :

```bash
cp .env.example .env
```

Puis modifier le fichier `.env` selon votre environnement :

### 🍎 **MAMP (macOS)**
```env
DB_HOST=127.0.0.1
DB_PORT=8889
DB_NAME=job
DB_USER=root
DB_PASS=root
DB_CHARSET=utf8mb4
```

### 🪟 **XAMPP/WAMP (Windows)**
```env
DB_HOST=localhost
DB_PORT=3306
DB_NAME=job
DB_USER=root
DB_PASS=
DB_CHARSET=utf8mb4
```

### 🐧 **MySQL Standard (Linux)**
```env
DB_HOST=localhost
DB_PORT=3306
DB_NAME=job
DB_USER=your_username
DB_PASS=your_password
DB_CHARSET=utf8mb4
```

## 🚫 **À NE JAMAIS FAIRE**
- Ne JAMAIS commiter le fichier `.env`
- Ne JAMAIS pousser vos configurations locales
- Toujours utiliser `.env.example` comme modèle

## ✅ **Workflow recommandé**
1. Cloner le projet
2. Copier `.env.example` vers `.env`
3. Adapter la configuration à votre environnement local
4. Travailler normalement (`.env` est ignoré par Git)