<template>

    <div class="d-flex flex-column align-items-center justify-content-center pt-4">
        <img src="/storage/images/error-sad-kanye.png" alt="">
        <div class="ms-3 h5 text-danger d-flex flex-column justify-content-center align-items-center text-center">
            <p>Oops, there has been a problem trying to {{action}} :(</p>
            <p>Please, try again later</p>
        </div>
    </div>

</template>

<script>
export default{
    name: "FetchErrorComponent",
    props:{
        action:""
    }
}

</script>