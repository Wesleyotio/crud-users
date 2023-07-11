# Crud-Users
 Esta aplicação tem como objetivo construir um CRUD de usuários

# Instruções para uso da aplicação após fazer `git clone`

1. Copie o arquivo .env.example para .env usando o comando
   
```sh
cp .env.example .env
```

2. Levante os containers docker usando 

```sh
docker-compose up -d --build
```
3. Em seguida entre no container da aplicação 

```sh
docker exec -it app bash
```
4. Agora dentro dele vamos em busca de nossas dependências 

```sh
composer install
```
5. Para testar se nossa API está funcioando como esperado execute os testes

```sh
php artisan test
``` 

6. Podemos agora popular o banco usando 

```sh
php artisan migrate:fresh --seed
```
7. Podemos agora instalar nossas dependências para o `VueJs` 

```sh
npm install
```
8. Iniciamos usando a versão de desenvolvimento 

```sh
npm run dev
```
9. Para acessar a aplicação em funcionamento click no link [Localhost](http://localhost) 

## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
