<template>

    <div class="bg-container d-flex flex-column" :class="{'fade-in': showImage}">
        <div class="d-flex flex-grow-1 flex-column justify-content-lg-center mt-5 mt-lg-0">
            <div class="mx-1 mx-md-5 px-2 px-md-5  d-flex justify-content-center justify-content-lg-start">
                <RouterView></RouterView>
            </div>
        </div>
    </div>

</template>

<script>
import LoginComponent from './LoginComponent.vue';

    export default {
        name: "IndexComponent",
        components:{
            LoginComponent
        },
    data() {
        return{
            showImage: false
        }
    },
    mounted() {
        setTimeout(() => {
          this.showImage = true;
        },10);
    }
    }
</script>

<style>
    .bg-container {
      position: relative;
      height: 100vh;
      overflow: hidden;
    }
    .bg-container::before {
      content: '';
      position: absolute;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      background-size: cover;
      background: url('/storage/images/wallpaper-lg.jpg') no-repeat center center fixed;
      transition: opacity 4s;
      opacity: 0;
      z-index: -1;
    }
    @media (max-width: 990px) {
        .bg-container::before{
            background: url('/storage/images/wallpaper-md.jpg') no-repeat center center fixed;
        }
    }
    @media (max-width: 420px) {
        .bg-container::before{
            background: url('/storage/images/wallpaper-sm.jpg') no-repeat center center fixed;
        }
    }
    .fade-in::before {
      opacity: 1;
    }
</style>
