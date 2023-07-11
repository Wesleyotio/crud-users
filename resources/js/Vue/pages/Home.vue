<template>
    <div>
        <div class="justify-content-center container-fluid my-4 ">
        <Search></Search>
        <ListUsers v-if="viewTable"></ListUsers>
        </div>
    </div>
</template>

<script>
import Search from '../components/Search.vue'
import ListUsers from '../components/ListUsers.vue'
export default {
    components: { Search, ListUsers },
    data(){
        return{
            viewTable: true
        }
    },
    mounted(){
        const logado = localStorage.getItem('isLoggedIn')
        if( logado === 'true'){
            axios.get('api/user').then((response) => {
                this.corruentUser = response.data
            }).catch((error) => {
                this.emitter.emit('isLoggedOff', false)
                this.$router.push('/login')
                console.error(error)
            })
        }
        this.emitter.on('viewTable', viewTable => {
            console.log(viewTable);
            this.viewTable = viewTable
            //
        })
    }
}
</script>

<style>
</style>
