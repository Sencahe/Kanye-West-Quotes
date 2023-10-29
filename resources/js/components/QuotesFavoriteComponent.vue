<template>

    <div class="d-flex flex-column justify-content-center align-items-center">
        <div class="d-flex justify-content-center">
            <h2 class="text-white mb-5 me-3 text-center">Your Favorite Kanye Quotes!</h2>
            <div>
                <RouterLink to="/quotes" class="btn btn-success">Go to Generator</RouterLink>
            </div>
        </div>
        <ul class="list-group w-100">
            <li v-for="quoteObj in favoriteQuotes" key="quote" class="list-group-item list-group-item-action d-flex justify-content-between" >

                <p class="m-0 p-0">{{ quoteObj.quote }}</p>
                <div class="d-flex flex-column justify-content-center">
                    <i @click.prevent="removeFromFavorites(quoteObj.id)" class="fa-solid fa-trash ms-3 text-danger" ></i>
                </div>

            </li>
        </ul>
        <div v-if="favoriteQuotes.length == 0">
            <p class="text-center text-white h5">You don't have any quotes saved in your favorites</p>
        </div>

    </div>

</template>

<script>
import axios from 'axios';

export default {
    name: "FavoriteQuotesComponent",
    data() {
        return {
            favoriteQuotes: []
        }
    },
    beforeRouteEnter(to, from, next) {
        next(vm => {
            vm.fetchFavoritesQuotes();;
        });
    },
    methods: {
        fetchFavoritesQuotes() {
            axios.get("/request/quotes")
                .then(response => {
                    this.favoriteQuotes = response.data;
                }).catch(error => {
                    //
                });
        },
        removeFromFavorites(quoteId) {
            axios.delete("/request/quote/" + quoteId)
                .then(response => {
                    this.favoriteQuotes = this.favoriteQuotes.filter(obj => obj.id !== response.data.id);
                }).catch(error => {
                    //
                })
        }
    }
}
</script>
