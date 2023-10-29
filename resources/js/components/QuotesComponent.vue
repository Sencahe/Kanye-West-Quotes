<template>
    <div class="min-vh-100 py-2 py-md-5 d-flex flex-column justify-content-center align-items-center overflow-hidden bg-content">

        <h1 class="text-center text-white">Kanye West Quotes App!!</h1>
        <h3 class="text-center text-white mb-5">Hi {{user.name}} {{user.last_name}}</h3>

        <div v-if="!loginOut" class="col-11 col-md-11 col-lg-6 border rounded py-3 px-2 px-md-3 p-lg-4  ">
            <RouterView v-slot="{ Component }">
                <KeepAlive>
                    <component :is="Component" :key="$route.fullPath"></component>
                </KeepAlive>
            </RouterView>
        </div>

        <div v-else>
            <p class="h3 text-light">Loging out...</p>
        </div>

        <div  v-if="!loginOut" class="mt-2">
            <button @click.prevent="logout" class="btn btn-success mt-5">Logout</button>
        </div>

    </div>
</template>

<script>
import axios from 'axios';

export default {
    name: "QuotesController",
    data() {
        return {
            loginOut: false,
            user: {}
        }
    },
    mounted(){
        this.user = JSON.parse(localStorage.getItem('user'));
    },
    methods:{
        logout(){
            this.loginOut = true;
            axios.post("/auth/logout")
            .then(response => {
                this.$router.push("/");
            }).catch(error =>{
                //
                this.loginOut=false;
            });
        }
    }
}
</script>

<style>
.bg-content{
    background-size: 100% 100%;
    background: url('/storage/images/wallpaper-main-lg.jpg') no-repeat center center fixed;
    background-color: black;
}

@media (max-width: 990px) {
    .bg-content{
        background: url('/storage/images/wallpaper-main-md.jpg') no-repeat center center fixed;
    }
}

@media (max-width: 420px) {
    .bg-content{
        background: url('/storage/images/wallpaper-main-sm.jpg') no-repeat center center fixed;
    }
}

</style>