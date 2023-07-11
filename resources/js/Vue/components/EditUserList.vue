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
            <button type="submit" class="btn btn-info " style="width: 100%;" @click="update"> Salvar Alterações </button>
        </form>
    </div>
  </div>
</template>

<script>
export default {
    props: [
        "id"
    ],
    data(){
        return {
            user: {
                name: '',
                email: '',
                phone: ''
            },
            pathImage: null,
            errorEmailMessage: null,
            errors: null
        }
    },
    created(){
        var token = localStorage.getItem('token')

            axios.get('/sanctum/csrf-cookie').then(response =>{
                axios.get(`api/show/${this.id}`,
                {
                    headers: {
                        'Authorization': `Bearer ${token}`,
                    }
                })
                .then(response => {
                    console.log(response.data);
                    if (response.data.success) {
                        this.user.name      = response.data.users.name
                        this.user.email     = response.data.users.email
                        this.user.phone     = response.data.users.phone
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
    methods: {
        update(e){
            var token = localStorage.getItem('token')
            e.preventDefault()
            if(this.errorEmailMessage !== null) {
                alert('Verifique se todos os campos estão preenchidos corretamente!')
                return
            }
            axios.get('/sanctum/csrf-cookie').then(response =>{
                axios.put(`api/update-user/${this.id}`,
                {
                    name: this.user.name,
                    email: this.user.email,
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
                        alert(response.data.message)
                        this.$router.push('/')

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
        }
    },
    computed:{
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
