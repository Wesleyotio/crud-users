# Crud-Users
 Esta aplicação tem como objetivo construir um CRUD de usuários

 ## Dependências
 - **Laravel Sanctum**
   - Dependência responsável pela autenticação do usuário
   - Guia de instalação do [Laravel Sanctum](https://laravel.com/docs/8.x/sanctum#installation)
  
 - **NPM** 
   - Dependência necessária para instalar as dependências seguintes.
   -  Digite no terminal o comando `npm install` 
- **VueJS**
  - Para este projeto usamos as seguintes dependências do VueJS : mitt, vue, vue-router, vue-axios
  - Usando o comando `npm install mitt --save` é instalada a dependência responsável por emitir eventos entre os componentes da aplicação.
  - Usando o comando `npm install vue --save` para instalação do Vue.
  - Usando o comando `npm install vue-router@next vue-axios --save` essas dependências são responsáveis respetivamente gerenciar as rotas dos componentes e fazer a comunicação da API do Laravel com o VueJs
## Levantando a aplicação 
 - **Containers**
   - Inicializamos a aplicação fazendo uso do comando `docker-compose up -d`
   - Com todos os containers em funcionamento usamos o seguinte comando `docker exec -it app bash` para entrar no container com nossa aplicação PHP
   - Para realizarmos a instalação de nosso pacotes php usamos `composer install` em seguida damos o comando `php artisan migrate` para inicializar as tabelas do banco de dados, tá quase lá...
   - Agora no mesmo container damos o comando `npm install` e em seguida o comando `npm run dev --hot` e tá de boas 
   - Para acessar a aplicação em funcionamento click no link [Localhost](http://localhost) 
## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
