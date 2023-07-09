<?php

namespace Tests\Unit;

use App\Models\User;
use Faker\Factory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class UserTest extends TestCase
{
     /**
     * A basic unit test example.
     *
     * @return void
     */
    use RefreshDatabase;


    public function test_users_can_authenticate_using_the_login_json() {
        $user = User::factory()->create();

        $response = $this->post('/api/login', [
            'email' => $user->email,
            'password' => '12345678',
        ]);

        $response->assertStatus(200)
                ->assertJson([
                    'success'   => true,
                    'message'   => "Usuário logado com sucesso"
                ]);
    }

    public function test_users_can_not_authenticate_with_invalid_password() {
        $user = User::factory()->create();

        $response = $this->post('api/login', [
            'email' => $user->email,
            'password' => Hash::make('87654321'),
        ]);

        $response->assertStatus(401)
                ->assertJson([
                    'success'   => false,
                    'message'   => "email ou senha inválidos"
                ]);
    }

    public function test_users_can_logout_using_json() {
        $user = User::factory()->create();

        $response = $this->post('/api/login', [
            'email' => $user->email,
            'password' => '12345678',
        ]);

        $token = $response['token'];


        $response = $this->get('/api/logout',
        [
            "Accept"=>"application/json",
            "Authorization"  => 'Bearer'.$token,

        ]);

        $response->assertStatus(200)
                ->assertJson([
                    'success'   => true,
                    'message'   => "Usuário deslogado com sucesso"
                ]);


    }

    public function test_update_info_of_user() {



        $user = User::factory()->create();

        $response = $this->post('/api/login', [
            'email' => $user->email,
            'password' => '12345678',
        ]);

        $token = $response['token'];

        $response = $this->put('/api/update',
        [
            'name'      => 'Jao Tester',
            'email'     => 'jaosilva@teste.com',
            'phone'     => '91988388310',
        ],
        [
            "Accept"=>"application/json",
            "Authorization"  => 'Bearer'.$token,
        ]);

        $response->assertJson([
            'success' => 'true',
            'message' => 'Usuário atualizado com sucesso'
        ]);
    }

    public function test_register_user() {

        $faker = Factory::create();
        $faker->addProvider(new \Faker\Provider\pt_BR\Person($faker));
        $faker->addProvider(new \Faker\Provider\pt_BR\Address($faker));
        $faker->addProvider(new \Faker\Provider\pt_BR\Company($faker));
        $faker->addProvider(new \Faker\Provider\pt_BR\PhoneNumber($faker));
        $faker->addProvider(new \Faker\Provider\Internet($faker));

        $response = $this->post('/api/register', [
            'name' =>  $faker->name,
            'email' => $faker->freeEmail,
            'phone' => $faker->phoneNumberCleared,
            'password' => '12345678', // password

        ]);
        $response->assertStatus(200)
                ->assertJson([
                    'success'   => true,
                    'message'   => "Usuário registrado e logado com sucesso"
                ]);
    }

    public function test_list_users() {
        $users = User::factory()->count(2)->create();

        $response = $this->post('/api/login', [
            'email' => $users->get(1)->email,
            'password' => '12345678',
        ]);

        $token = $response['token'];

        $response = $this->get('/api/list/', [
            "Accept"=>"application/json",
            "Authorization"  => 'Bearer'.$token,
        ]);
        $response->assertStatus(200)
        ->assertJson([
            'success'   => true,
            'users'     => $users->toArray(),
        ]);
    }

    public function test_show_user() {
        $users = User::factory()->count(5)->create();

        $response = $this->post('/api/login', [
            'email' => $users->get(1)->email,
            'password' => '12345678',
        ]);

        $token = $response['token'];

        $response = $this->get('/api/show/'.$users->get(3)->id, [
            "Accept"=>"application/json",
            "Authorization"  => 'Bearer'.$token,
        ]);
        $response->assertStatus(200)
        ->assertJson([
            'success'   => true,
            'users'     => $users->get(3)->toArray(),
        ]);
    }

    public function test_delete_user() {

        $user = User::factory()->create();
        $userforDelete = User::factory()->create();

        $response = $this->post('/api/login', [
            'email' => $user->email,
            'password' => '12345678',
        ]);

        $token = $response['token'];

        $response = $this->delete('/api/delete/'.$userforDelete->id, [
            "Accept"=>"application/json",
            "Authorization"  => 'Bearer'.$token,
        ]);
        $response->assertStatus(200)
        ->assertJson([
            'success' => 'true',
            'message' => 'Usuário removido com sucesso'
        ]);
    }

    public function test_import_image_user() {

        Storage::fake('storage');

        $file = UploadedFile::fake()->image('user_image.jpg');
        $user = User::factory()->create();

        $response = $this->post('/api/login', [
            'email' => $user->email,
            'password' => '12345678',
        ]);

        $token = $response['token'];

        $response = $this->post('/api/image-user/',[
            'userImage' => $file
        ],
         [
            'Content-Type'  =>  'multipart/form-data',
            "Authorization" =>  'Bearer'.$token,
        ]);

        $response->assertStatus(200)
        ->assertJson([
            'success' => true,
            'imagemPath' => 'images/'.$file->getClientOriginalName(),
            'message' => 'Imagem enviada com sucesso!'
        ]);


    }
}
