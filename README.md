# README

<p align="center">Mini-Biblioteca</p>


# OBS

    * Seguindo o guia de instalação, por padrão já virá com um usuario ADM pré configurado.
    * As rotas referente a BOOK só serão executadas com um usuário Administrador. 

      email:admin@admin.com
      password: 12345678

    * Após o cadastro de um usuário normal, faça o login e pegue o token para a execução das rotas de User.
    * A rotas do tipo GET exceto as de listagem necessitarão de um 'id'. Informe na QUERY do insomnia ou postman
    
<br>


# Requisitos

    * PHP 7.4
    * Laravel 8
    * composer 2
    * MySQL



# Instalação

<p> Projeto construido com php 7.4 e larave 8 e MySQL. Foi utilizando insomnia para testar API<br>

Rode os seguintes comandos antes da excecução de qualquer rota.<br>

    * composer install
    * composer update
<br>

<br>

    *Verifique as informações para a conexão com o DB no arquivo .env
    DB_CONNECTION=mysql
    DB_HOST=127.0.0.1
    DB_PORT=3306
    DB_DATABASE=<seu database>
    DB_USERNAME=root
    DB_PASSWORD=<sua senha>

<br>

 Execute o comando abaixo para configurar a estrutura do banco de dados do projeto.
<br>
<strong>php artisan migrate:fresh --seed</strong>
</p>

Em seguida rode o seguinte comando para inicicar o sevidor do Laravel para executarmos as rotas.
<br>
<strong>php artisan server</strong>
</p>
<br>

# Swagger

   <br>
   
    * http://localhost:8000/api/swagger

