# Définir UID/GID de l'utilisateur local
export PUID=$(id -u)
export PGID=$(id -g)

# Lancer les conteneurs
docker compose up -d --build

# Installer les dépendances Laravel
docker compose run --rm app composer install

# Générer la clé d'application
docker compose exec app php artisan key:generate

# Migrer la base de données
docker compose exec app php artisan migrate

# Lier le storage
docker compose exec app php artisan storage:link

# Node/Vite pour le front
docker compose run --rm node npm install
docker compose run --rm node npm run dev
