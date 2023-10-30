<template>
        <div class="form-control border text-start m-0" style="max-width: 450px">

            <h1 class="h3 text-center">Kanye West Quotes!</h1>

            <form class="d-flex flex-column">
                <p v-if="registerAttemptFailed != ''" class="text-danger pt-2">{{ registerAttemptFailed }}</p>

                <!-- Name-->
                <div class="py-2" :class="{ 'text-danger': userErrorData.name }">
                    <label for="name" class="">Name</label>
                    <label v-if="userErrorData.name" for="name" class=" ms-1"> - {{ userErrorData.name[0] }}</label>
                </div>
                <input :class="{ 'border-danger': userErrorData.name }" type="text" name="name" v-model="user.name">
                
                <!-- Last Name-->
                <div class="py-2" :class="{ 'text-danger': userErrorData.last_name }">
                    <label for="last_name" class="">Last Name</label>
                    <label v-if="userErrorData.last_name" for="last_name" class=" ms-1"> - {{ userErrorData.last_name[0] }}</label>
                </div>
                <input :class="{ 'border-danger': userErrorData.last_name }"  type="text" name="last_name" v-model="user.last_name">

                <!-- Email-->
                <div class="py-2" :class="{ 'text-danger': userErrorData.email }">
                    <label for="email" class="">Email</label>
                    <label v-if="userErrorData.email" for="email" class=" ms-1"> - {{ userErrorData.email[0] }}</label>
                </div>
                <input :class="{ 'border-danger': userErrorData.email }" type="email" email="email" v-model="user.email">

                <!-- Password-->
                <div class="py-2" :class="{ 'text-danger': userErrorData.password }">
                    <label for="password" class="">Password</label>
                    <label v-if="userErrorData.password" for="password" class=" ms-1"> - {{ userErrorData.password[0] }}</label>
                </div>
                <input :class="{ 'border-danger': userErrorData.password }" type="password" name="password" v-model="user.password">

                <!-- Confirm Password-->
                <div class="py-2" :class="{ 'text-danger': userErrorData.confirm_password }">
                    <label for="confirm_password" class="">Confirm Password</label>
                    <label v-if="userErrorData.confirm_password" for="password" class=" ms-1"> - {{ userErrorData.confirm_password[0] }}</label>
                </div>
                <input :class="{ 'border-danger': userErrorData.confirm_password }" type="password" name="confirm_password" v-model="user.confirm_password">

                <!-- Buttons -->
                <div class="d-flex align-items-center justify-content-between">
                    <button @click.prevent="register()" class="mt-4 mb-2 btn btn-success w-25" >Register</button>
                    <div class="pt-3 ps-3 d-flex flex-column">
                        <p class="text-success text-center m-0 ">Already have an account? </p>
                        <RouterLink to="/" class="text-success text-center">Login!</RouterLink>
                    </div> 
                </div>
               
            </form>

        </div>
</template>


<script>
import Axios from "axios"

export default {
    name: "RegisterComponent",
    data() {
        return {
            user: {
                name: "",
                last_name: "",
                email: "",
                password: "",
                confirm_password: ""
            },
            userErrorData: {},
            registerAttemptFailed: "",
        }
    },
    methods: {
        register() {
            Axios.post('/auth/register', this.user)
                .then(response => {
                    //console.log(response);
                    this.$router.push("/");
                    this.$swal({
                        icon: 'success',
                        title: 'You account was succesfully created!',
                        text: `Please proceed to login with ${this.user.email} email account.`,
                    })
                })
                .catch(error => {
                    //console.log(error)
                    if (error.response.status == 422) {
                        this.userErrorData = error.response.data;
                        //console.log(JSON.stringify(this.userErrorData));
                    } else {
                        this.registerAttemptFailed = "There has been an error. Please, try again later";
                        this.userErrorData = {}
                    }

                });
        }
    }

}
</script>
