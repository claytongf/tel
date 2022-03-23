<p align="center">Laravel - Nuxt with Docker Compose (Demo only)</p>

<p align="center">
  This is a simple setup to work with docker-compose, laravel and NuxtJS
</p>

## About

This template uses Laravel and Nuxt for backend and frontend with Mysql configured.
Also, the template is ready to use with docker-compose
I've used Laravel 9 and NuxtJs 2.

## Instalation

First, in docker-compose.yaml file, you need to change the name of the containers as you like.

- use this command to run the containers:<br/>
  <code>docker-compose up --build</code><br/>
  It may take a while. All the containers should be running.<br/><br/>
- To enter the bash inside the container, you need to run the following command:<br>
  <code>docker-compose exec app bash</code><br/>
  The localhost:3000 form the Frontend should be available<br/>
  To test if Laravel is running, go to localhost:8000<br/>
  If you want to access the database, you can access via Phpmyadmin by localhost:8001

## Notes

This template has phpmyadmin configured in docker-compose. If you don't need it, just remove it from docker-compose.yaml
You need to wait until the frontend is up. Check the logs of the container to verify it.
For testing, use login: teste@teste.com.br password: 123456
You may create new users after logged in
