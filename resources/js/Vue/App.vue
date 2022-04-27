<template>
    <div>
        <Navbar/>
        <router-view/>
    </div>
</template>

<script>
import Login from './pages/Login.vue'
import Register from './pages/Register.vue'
import Home from './pages/Home.vue'
import Navbar from './components/Navbar.vue'
export default {
    components: { Navbar ,Login, Register, Home },
    data(){
        return{

        }
    },
    methods: {
    },
}
</script>

<style>
</style>
