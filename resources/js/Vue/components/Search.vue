<template>
    <div>
        <div class="col-8">
            <form>
                <div class="input-group h2">
                    <input
                        name="data[search]"
                        class="form-control"
                        id="busca_user"
                        type="text"
                        placeholder="Nomes, telefones e emails"
                        v-model="search"
                    />
                    <p>{{ emptySearch }}</p>
                    <div class="input-group-append">
                        <button
                            type="submit"
                            class="input-group-text text-light"
                            style="background-color: #3cb371"
                            @click="searchUser"
                        >
                            <i class="material-icons">search</i>
                        </button>
                    </div>
                </div>
            </form>
        </div>
        <div id="list" class="row" v-if="viewTable">
            <div class="table-responsive col-md-12">
                <table id="tabela_user" class="table table-striped">
                    <thead>
                        <tr>
                            <th scope="col">Nome</th>
                            <th scope="col">Telefone</th>
                            <th scope="col">Email</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="user in users" :key="user.id">
                            <td>{{ user.name }}</td>
                            <td>{{ user.phone }}</td>
                            <td>{{ user.email }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    data() {
        return {
            viewTable: false,
            users: [],
            search: ''
        };
    },
    computed: {
        emptySearch(){
            if(this.search === ""){
                this.viewTable = false
                this.emitter.emit('viewTable', true)
            }
        }
    },
    methods: {
        searchUser(e){
            e.preventDefault(),
            axios.get(`api/show/${this.search}`).then((response) => {
                if(response.data.success){
                    this.emitter.emit('viewTable', false)
                    this.users = response.data.table
                    this.viewTable = true
                }
            }).catch((error) => {
               // this.emitter.emit('isLoggedOff', false)
                //this.$router.push('/login')
                console.error(error)
            })
        }
    }
};
</script>

<style>
</style>
