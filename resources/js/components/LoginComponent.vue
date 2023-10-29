<template>
    
        <div class="form-control border text-start m-0" style="max-width: 450px">
            <h1 class="h3 text-center">Kanye West Quotes!</h1>
            <form class="d-flex flex-column">

                <p v-if="loginAttemptFailed != ''" class="text-danger pt-2">{{ loginAttemptFailed }}</p>

                <label for="email" class="py-2">Email</label>
                <input type="email" email="email" v-model="user.email">

                <label for="password" class="py-2">Password</label>
                <input type="password" name="password" v-model="user.password">

                <div class="d-flex align-items-center justify-content-between">
                    <button @click.prevent="login()" class="mt-4 mb-2 btn btn-success w-25" >Register</button>
                    <div class="pt-3 ps-3 d-flex flex-column">
                        <p class="text-success text-center m-0 ">You don't have an account? </p>
                        <RouterLink to="/register" class="text-success text-center">Register here!</RouterLink>
                    </div> 
                </div>
            </form>

        </div>

</template>


<script>
import Axios from "axios"

export default {
    name: "LoginComponent",
    data() {
        return {
            user: {
                email: "",
                password: ""
            },
            loginAttemptFailed: "",
        }
    },
    methods: {
        login() {
            if (this.user.email === "" || this.user.password === "") {
                this.loginAttemptFailed = "Email and/or Password must be filled"
                return;
            } else {
                this.loginAttemptFailed = "";
            }

            Axios.post('/auth', {
                email: this.user.email,
                password: this.user.password
            }, {
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json'
                }
            })
            .then(response => {

                localStorage.setItem("user", JSON.stringify(response.data.user));
                this.$router.push("/quotes");
            })
            .catch(error => {
                if(error.response.status == 401){
                    this.loginAttemptFailed = "Username and/or Password are incorrect";
                } else {
                    this.loginAttemptFailed = "There has been an error. Please, try again later";
                }

            });
        }
    }

}
</script>
