<template>
    <div class="my-2 my-md-5 d-flex flex-column justify-content-center align-items-center overflow-hidden">

        <h1 class="text-center text-white mb-5 overflow-hidden">Kanye West Quotes App!!</h1>

        <div v-if="!loginOut" class="col-11 col-md-11 col-lg-6 border py-3 px-2 px-md-3 p-lg-4  rounded">
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
            loginOut: false
        }
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
