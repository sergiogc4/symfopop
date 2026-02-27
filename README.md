# SymfoPop - Mercat de Segona Mà

Aplicació web de mercat de segona mà desenvolupada amb Symfony 6.

## Tecnologies
- Symfony 6
- Doctrine ORM
- Twig
- Bootstrap 5
- MySQL

## Instal·lació

1. Clona el repositori:
git clone https://github.com/sergiogc4/symfopop.git
cd symfopop

2. Instal·la les dependències:
composer install

3. Configura la base de dades al fitxer .env:
DATABASE_URL="mysql://usuari:contrasenya@127.0.0.1:3306/symfopop"

4. Crea la base de dades i executa les migracions:
php bin/console doctrine:database:create
php bin/console doctrine:migrations:migrate

5. Carrega les dades de prova:
php bin/console doctrine:fixtures:load

6. Inicia el servidor:
symfony serve

7. Obre el navegador a http://127.0.0.1:8000

## Funcionalitats
- Registre i login d'usuaris
- Llistat públic de productes
- Crear, editar i esborrar productes propis
- Validació de permisos
- Protecció CSRF
