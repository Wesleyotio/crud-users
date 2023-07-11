<template>
    <div class="container-fluid d-flex justify-content-center h-100" style="margin-top: 10%" >
      <div class="card-body d-flex justify-content-center">

        <form  method="POST" autocomplete="on" >
            <div class="form-group">
                <label for="exampleInputEmail">Email:</label>
                <input type="email" name="user_email"  class="form-control"
                    id="inputEmail" aria-describedby="emailHelp"
                    v-model="user.email"
                    required
                    placeholder="Enter email">
                <small id="msgemail" class="form-text text-muted"></small>
                <div class="alert alert-danger" role="alert" v-if="errorEmailMessage != null" style="align-self: center;">
                        {{ errorEmailMessage }}
                </div>
                <p>{{ validateFieldEmail }}</p>
            </div>
            <div class="form-group">
                <label for="exampleInputName">Nome:</label>
                <input type="text" name="user_name" class="form-control"
                pattern="[A-Za-z]+"
                required
                v-model="user.name"
                placeholder="Nome">
            </div>
            <div class="form-group">
                <label for="exampleInputName">Telefone:</label>
                <input type="tel" name="user_phone" class="form-control"
                pattern="[0-9]{2}-[0-9]{4,5}-[0-9]{4}"
                maxlength="11"
                required
                v-model="user.phone"
                placeholder="Telefone">
            </div>
            <div class="form-group">
                <label for="exampleInputPassword">Senha:</label>
                <input type="password" name="user_password" class="form-control"
                v-model="user.password"
                placeholder="password">
                <div class="alert alert-danger" role="alert" v-if="errorPasswordMessage != null" style="align-self: center;">
                        {{ errorPasswordMessage }}
                </div>
                <p>{{ validatePassword }}</p>
            </div>
            <button type="submit" class="btn btn-info " style="width: 100%;" @click="createUser"> Criar usuário </button>
        </form>
    </div>
  </div>
</template>

<script>
export default {
    data(){
        return {
            user: {
                name: '',
                email: '',
                password: '',
                phone: ''
            },
            errorPasswordMessage: null,
            errorEmailMessage: null
        }
    },
    methods: {
        createUser(e){
            var token = localStorage.getItem('token')
            e.preventDefault()
            if(this.errorEmailMessage !== null || this.errorPasswordMessage !== null) {
                alert('Verifique se todos os campos estão preenchidos corretamente!')
                return
            }
            axios.get('/sanctum/csrf-cookie').then(response =>{
                axios.post('api/create-user', {
                    name: this.user.name,
                    email: this.user.email,
                    password: this.user.password,
                    phone: this.user.phone
                },
                {
                    headers: {
                        'Authorization': `Bearer ${token}`,
                    }
                })
                .then(response => {
                    console.log(response.data);
                    if (response.data.success) {
                        alert('Usuário criado com Sucesso!')
                        this.$router.push('/')
                    }else{
                        this.errors = response.data.message
                    }
                })
                .catch(error => {
                    console.error(error.response.data.message);
                    alert(error.response.data.message)
                    // this.errors = error.response.data.message;
                });
            })
        }
    },

    computed:{
        validatePassword(){
            if( this.user.password.length < 8){
                this.errorPasswordMessage = 'Escreva uma senha com pelo menos oito caracteres '
            }else{
                this.errorPasswordMessage = null
            }

        },
        validateFieldEmail(){
            if(this.user.email === '') {
                this.errorEmailMessage = 'Esse campo é requerido para criação do usuário'
            } else {
                this.errorEmailMessage = null
            }
        }
    }
}
</script>

<style>
</style>
