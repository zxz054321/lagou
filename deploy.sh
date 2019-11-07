git fetch --all
git reset --hard origin/master

composer install --no-dev

chown -R www.www .
chmod +x deploy.sh
