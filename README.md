# NEGAHBANE SITE
**Website** [https://hubcafe.ir](https://hubcafe.ir])
<br>
**UI** [https://www.figma.com/file/AEl21tNXDoEarUjV0MqIzX/cafe-hub-pro?node-id=0%3A1](https://www.figma.com/file/AEl21tNXDoEarUjV0MqIzX/cafe-hub-pro?node-id=0%3A1)
<br>
**FRONT** [https://gitlab.com/mahsanaseri/negahbane-site-front](https://gitlab.com/mahsanaseri/negahbane-site-front)

## Admin
Login Address: [https://gitlab.com/mamady83/cafehub-front](https://gitlab.com/mamady83/cafehub-front)
<br>
Username: admin@cafehub.com
<br>
Password: CafeHubAdmin123qwe!@#


## Initial Deployment
run these commands in order, when first deploying the project
```
composer install
php artisn key:generate
php artisan voyager:install
php artisan migrate
php artisan db:seed
```

## Pipeline
push to branch master, project will be deployed to server and these commands will execute in order
```
php artisan migrate
php artisan config:cache
php artisan cache:clear
php artisan optimize:clear
```
