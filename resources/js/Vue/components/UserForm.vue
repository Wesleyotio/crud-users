<template>
   <div class="container-fluid d-flex justify-content-center h-100" style="margin-top: 10%" >
      <div class="card-body d-flex justify-content-center">
        <div class="alert alert-danger" role="alert" v-if="user.error !== null" style="align-self: center;">
                {{ user.error }}
        </div>
        <form  method="POST" autocomplete="on" >
            <div class="form-group">
                <label for="exampleInputEmail1">Email:</label>
                <input type="email" name="user_email"  class="form-control"
                    id="inputEmail" aria-describedby="emailHelp"
                    v-model="user.email"
                    placeholder="Enter email">
                <small id="msgemail" class="form-text text-muted"></small>
            </div>
            <div class="form-group">
                <label for="exampleInputName">Nome:</label>
                <input type="text" name="user_name" class="form-control"
                v-model="user.name"
                placeholder="Nome">
            </div>
            <div class="form-group">
                <label for="exampleInputName">Senha:</label>
                <input type="password" name="user_name" class="form-control"
                v-model="user.password"
                placeholder="password">
            </div>
            <button type="submit" class="btn btn-info " style="width: 100%;" @click="register"> Registar </button>
        </form>
    </div>
  </div>
</template>

<script>
export default {
}
</script>

<style>
</style>
