# README

<p align="center">Mini-Biblioteca</p>

<p align="center">
    <a href="#Sobre">Sobre</a> -
    <a href="#Session">Sessionr</a> -
    <a href="#User">User</a> -
    <a href="#Book">Book</a> -
</p>

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



# Sobre

<p> Projeto construido com php 7.4 e larave 8 e MySQL. Foi utilizando insomnia para testar API<br>

Rode os seguintes comandos antes da excecução de qualquer rota.<br>

<strong>composer install</strong>
<br>
<strong>composer update</strong>
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

Em seguida roda o seguinte comando para inicicar o sevidor do Laravel para executarmos as rotas.
<br>
<strong>php artisan server</strong>
</p>
<br>

# Session

<br>

<h3> Cadastrar User e Login <h3>
    * Rota: http://127.0.0.1:8000/api/account/store {POST}
    <br>
    * payload:  
     
	"name":"Trevor",
	"email":"gmail@gmail.com",
	"age":610,
	"cpf":"739.073.280-60",
	"password":"12345678",
	
	"street":"Rua almeida 29",
	"number":100,
	"city":"Valaquia",
	"cep":"57306-463"
<br>

<h3>Login <h3>
    * Rota: http://127.0.0.1:8000/api/account/login {POST}
    <br>
    * payload:

    "email":"gmail@gmail.com",
	"password":"12345678"


<br>

# User 

<h3> showUser <h3>
    * Rota: http://127.0.0.1:8000/api/user/show {GET}

<br>

<h3> updateUser <h3>
<br>
    * Rota: http://127.0.0.1:8000/api/user/update {PUT}
    <br>
    * payload:

    "id":10,
	"name":"Nelio Norte",
	"age":610,
	"cpf":"739.073.280-60",
	
	"street":"rua 1900",
	"number":521,
	"city":"Valaquia",
	"cep":"57306-463"
<br>


<h3> destroyUser <h3>
    * Rota: http://127.0.0.1:8000/api/user/delete {DELETE}
    <br>
    *payload:

    "id":1
<br>

<h3> addBook <h3>
    * Rota: http://127.0.0.1:8000/api/user/addbook {POST}
    <br>
    payload:

    "user_id":1,
	"book_id":1
<br>

# Book

<h3> ListBook <h3>

    *Rota: http://127.0.0.1:8000/api/books/index {GET}
<br>

<h3> ListUser <h3>

    *Rota: http://127.0.0.1:8000/api/books/user/index {GET}
<br>

<h3>Show Book <h3>

    *Rota: http://127.0.0.1:8000/api/books/show {GET}
    *Query: id: 1
<br>

<h3> saveBook <h3>
    *Rota: http://127.0.0.1:8000/api/books/store {POST}
    <br>
    payload: 

    "name":"As Aventuras de PI2",
	"author": "Nicolau Quinto",
	"edition":"Segunda",
	"publishing_company":"Ed Sentopeia"
<br>

<h3> updateBook <h3>
    *Rota: http://127.0.0.1:8000/api/books/update {PUT}
    <br>
    payload: 

    "id": 1
    "name":"As Aventuras de PI2",
	"author": "Nicolau Quinto",
	"edition":"Segunda",
	"publishing_company":"Ed Sentopeia"
<br>

<h3> destroyBook <h3>
    
    *Rota: http://127.0.0.1:8000/api/books/delete
    *payload: "id":1
<br>
