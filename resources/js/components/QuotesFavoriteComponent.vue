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
        <div v-if="favoriteQuotes.length == 0 && !hasFetchError && !isLoading">
            <p class="text-center text-white h5">You don't have any quotes saved in your favorites</p>
        </div>
        
        <FetchErrorComponent v-if="hasFetchError && !isLoading" :action="'get your favorite quotes'" ></FetchErrorComponent>

        <LoadingComponent v-show="isLoading"></LoadingComponent >

    </div>

</template>

<script>
import axios from 'axios';
import FetchErrorComponent from './FetchErrorComponent.vue';
import LoadingComponent from './LoadingComponent.vue';

export default {
    name: "FavoriteQuotesComponent",
    components: {
        FetchErrorComponent,
        LoadingComponent
    },
    data() {
        return {
            favoriteQuotes: [],
            hasFetchError: false,
            isLoading: false,
            isMounted: false,
        }
    },
    beforeRouteEnter(to, from, next) {
        next(vm => {
            if (vm.isMounted) {
                vm.getFavoriteQuotes();
            }
        });
    },
    mounted() {
        this.getFavoriteQuotes();
    },
    methods: {
        fetchFavoritesQuotes() {
            axios.get("/request/quotes")
                .then(response => {
                    this.favoriteQuotes = response.data;
                }).catch(error => {
                    this.hasFetchError = true;
                }).finally(response => {
                    this.isLoading = false;
                    this.isMounted = true;
                });
        },
        getFavoriteQuotes() {
            this.isLoading = true;
            this.hasFetchError = false;
            this.favoriteQuotes = [];
            setTimeout(() => {
                this.fetchFavoritesQuotes();
            }, "500");
        },
        removeFromFavorites(quoteId) {
            axios.delete("/request/quote/" + quoteId)
                .then(response => {
                    this.favoriteQuotes = this.favoriteQuotes.filter(obj => obj.id !== response.data.id);
                }).catch(error => {
                    this.$swal({
                        icon: 'error',
                        title: "There has been a problem trying to remove the quote from your favorites.",
                        text: "Please, try again later."
                    })
                })
        }
    }
}
</script>
