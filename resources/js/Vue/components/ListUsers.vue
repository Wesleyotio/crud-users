<template>
    <div id="list" class="row">
      <div class="col-12 m-2 ">
          <router-link :to="`/adduser`" v-if="this.id != null">
              <a class="btn btn-outline-success"> Adicionar usuário </a>
          </router-link>
      </div>
      <div class="table-responsive col-md-12">
          <table id="tabela_users" class="table table-striped">
              <thead>
                  <tr>
                      <th scope="col" >Status</th>
                      <th scope="col" >Nome</th>
                      <th scope="col" >Email</th>
                      <th scope="col" >Ação</th>
              </tr>
              </thead>
              <tbody>
                      <tr v-for="user in users" :key="user.id">
                          <td>{{user.status}}</td>
                          <td>{{user.name}}</td>
                          <td>{{user.email}}</td>
                          <td>
                              <div>
                                  <!-- <router-link :to="`/myusers/${user.id}`">
                                      <a class="btn btn-outline-warning mr-1" tabindex="-1" role="button" aria-disabled="true">Detalhes</a>
                                  </router-link> -->
                                  <router-link :to="`/user/${user.id}/editar`">
                                      <a class="btn btn-outline-primary ml-1 mr-1" tabindex="-1" role="button" aria-disabled="true">Editar</a>
                                  </router-link>
                                  <a class="btn btn-outline-danger ml-1" tabindex="-1" role="button" aria-disabled="true" @click="deletar(user.id)">Deletar</a>

                              </div>
                          </td>
                      </tr>

              </tbody>
          </table>
      </div>

  </div>
  </template>

  <script>
  export default {
      data(){
          return{
            id: null,
            users:[]
          }
      },
      created(){
          this.id = localStorage.getItem('userId')
          const token = localStorage.getItem('token')
          axios.get('/sanctum/csrf-cookie').then(response => {
              axios.get(`/api/list/`,{

                headers: {  'Authorization': `Bearer ${token}`}

              })
                .then(response =>{
                    console.log(response.data)
                    this.users = response.data.users
                })
              })
                .catch(error => {
                    console.error(error)
                });
      },

      methods: {
          deletar(id){
            const token = localStorage.getItem('token')
              axios.get("/sanctum/csrf-cookie").then((response) => {
                  axios.delete(`/api/delete/${id}`,{
                    headers: {  'Authorization': `Bearer ${token}`}
                  })
                  .then((response) => {
                      console.log(response.data);
                      if(response.data.success){
                          const result = this.users.find(user => user.id === id);
                          this.users.splice(this.users.indexOf(result), 1)
                      }else{
                          alert(response.data.message)
                      }
                  })
                  .catch( error =>{
                      console.log(error)
                  });
              })
              .catch(error =>{
                  console.error(error);
              });
          }
      }

  }
  </script>

  <style>

  </style>
