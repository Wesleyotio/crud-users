<template>
    <div class="container-fluid d-flex justify-content-center h-100" style="margin-top: 10%" >
      <div class="card-body d-flex justify-content-center">
        <div style="margin-right: 10%">
            <form  method="POST" autocomplete="on"  >
                <div class="form-group">
                    <img :src="pathImage" alt="Imagem Carregada" v-if="pathImage">
                    <p v-else>Nenhuma imagem carregada.</p>
                    <input type="file" @change="updateImage" accept="image/*">
                </div>
                <button type="submit" class="btn btn-info " style="width: 100%;" @click="sendImage"> Salvar Imagem </button>
            </form>
        </div>

        <div>
            <div class="alert alert-danger" role="alert" v-if="errors != null" style="align-self: center;">
                    {{ errors }}
            </div>
            <p>{{ validatePassword }}</p>
            <p>{{ validateFieldEmail }}</p>
            <form  method="PUT" autocomplete="on" >
                <div class="form-group">
                    <label >Nome:</label>
                    <input type="text" name="user_name" class="form-control"
                    pattern="[A-Za-z]+"
                    v-model="user.name"
                    placeholder="Nome">
                </div>
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
                    <label >Telefone:</label>
                    <input type="tel" name="user_phone" class="form-control"
                    pattern="[0-9]{2}-[0-9]{4,5}-[0-9]{4}"
                    maxlength="11"
                    v-model="user.phone"
                    placeholder="Telefone">
                </div>
                <div class="form-group">
                    <label> Senha: </label>
                    <input type="password" name="new_password"  class="form-control"
                        id="inputEmail" aria-describedby="emailHelp"
                        v-model="user.password"
                        placeholder="Senha">
                    <small id="msgpassword" class="form-text text-muted"></small>
                </div>
                <div class="form-group">
                    <label> Confirmar senha: </label>
                    <input type="password" name="confirm_password" class="form-control"
                    v-model="user.confirmPassword"
                    placeholder="confirmação da senha">
                    <div class="alert alert-danger" role="alert" v-if="errorPasswordMessage != null" style="align-self: center;">
                        {{ errorPasswordMessage }}
                    </div>
                    <p>{{ validatePassword }}</p>
                </div>
                <button type="submit" class="btn btn-info " style="width: 100%;" @click="update"> Salvar Alterações </button>
            </form>

        </div>
    </div>
  </div>
</template>

<script>
export default {

    data(){
        return {
            user: {
                id: null,
                name: '',
                email: '',
                phone: null,
                confirmPassword: '',
                password: '',
            },

            errorPasswordMessage: null,
            errorEmailMessage: null,

            pathImage: null,
            formData: null
        }
    },
    created(){
        this.user.name = localStorage.getItem('user')
        this.user.id = localStorage.getItem('userId')
        this.formData = new FormData()
    },
    methods: {
        update(e){
            var token = localStorage.getItem('token')
            e.preventDefault()
            if(this.errorEmailMessage !== null || this.errorPasswordMessage !== null) {
                alert('Verifique se todos os campos estão preenchidos corretamente!')
                return
            }
            axios.get('/sanctum/csrf-cookie').then(response =>{
                axios.put('api/update/',
                {
                    name: this.user.name,
                    email: this.user.email,
                    password: this.user.password,
                    phone: this.user.phone,
                } ,
                {
                    headers: {
                        'Authorization': `Bearer ${token}`,
                    }
                })
                .then(response => {
                    console.log(response.data);
                    if (response.data.success) {
                        this.emitter.emit('isLoggedIn', false)
                        localStorage.removeItem('userId')
                        localStorage.removeItem('user')
                        localStorage.removeItem('token')
                        this.$router.push('/login')
                    }else{
                        this.errors = response.data.message
                    }
                })
                .catch(error => {
                    console.error(error);
                    alert(error.response.message);
                });
            })

        },
        updateImage(e) {
            const fileImage = e.target.files[0];
            if( fileImage ) {
                const limitBytes = 5 * 1024 * 1024;
                if (fileImage.size > limitBytes) {
                    console.error('A imagem excede o limite de tamanho permitido.');
                    alert('A imagem excede o limite de tamanho permitido.')
                    return;
                }

                this.formData.append('userImage', fileImage);
                const reader = new FileReader();
                reader.onload = () => {
                    this.pathImage = reader.result;
                };
                reader.readAsDataURL(fileImage);

            }
        },
        sendImage(e){
            var token = localStorage.getItem('token')
            e.preventDefault()
            if(!this.formData) {
                alert('Nenhuma imagem foi carregada!')
                return
            }
            axios.get('/sanctum/csrf-cookie').then(response =>{
                axios.post('api/image-user/',this.formData,
                {
                    headers: {
                        'Authorization': `Bearer ${token}`,
                        'Content-Type': 'multipart/form-data'
                    }
                })
                .then(response => {
                    console.log(response.data);
                    if (response.data.success) {
                        alert('Imagem salva com sucesso!')
                    }else{
                        this.errors = response.data.message
                        alert(response.data.message);
                    }
                })
                .catch(error => {
                    console.error(error);
                    alert(error.response.message);
                });
            })

        }
    },
    computed:{
        validatePassword(){
            if((this.user.password !=="") && (this.user.confirmPassword !=="")){
                if((this.user.password !== this.user.confirmPassword)){
                    this.errorPasswordMessage = 'Senhas não são as mesmas'
                }else{
                    this.errorPasswordMessage = null
                }
            }else{
                this.errorPasswordMessage = null
            }

        },
        validateFieldEmail(){
            if(this.user.email === '') {
                this.errorEmailMessage = 'Esse campo é requerido para atualização do usuário'
            } else {
                this.errorEmailMessage = null
            }
        }
    }

}
</script>

<style>

</style>
