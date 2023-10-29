<template>

    <div class=" d-flex flex-column justify-content-center align-items-center">
        <div class="d-flex justify-content-center mb-5">
            <h2 class="text-white text-center  me-3">Random Quotes Generator!</h2>
            <div>
                <RouterLink to="/quotes/favorites" class="btn btn-success">Go to Favorites</RouterLink>
            </div>

        </div>
        <Transition name="fade">
            <div v-if="hasFetchError" class="d-flex flex-column align-items-center justify-content-center pt-4">
                <img src="/storage/images/error-sad-kanye.png" alt="">
                <div class="ms-3 h5 text-danger d-flex flex-column justify-content-center align-items-center text-center">
                    <p>Oops, there has been a problem trying to generate the Quotes :(</p>
                    <p>Please, try again later</p>
                </div>

            </div>
        </Transition>

        <Transition name="fade">
            <ul v-if="isLoaded" class="list-group ">
                <li v-for="quoteObj in randomQuotes" key="quote" class="list-group-item list-group-item-action d-flex justify-content-between" >

                    <p class="m-0 p-0">{{ quoteObj.quote }}</p>
                    <div class="d-flex flex-column justify-content-center">
                        <i @click.prevent="handleQuoteInFavorite(quoteObj.quote, findQuoteIdInFavorites(quoteObj.quote))" class="fa-solid fa-star ms-3" :class="{ 'text-gold': findQuoteIdInFavorites(quoteObj.quote) }" ></i>
                    </div>

                </li>
            </ul>
        </Transition>

        <Transition name="fade">
            <div v-if="isLoading">
                <img  src="/storage/images/loading.gif" alt="GIF" height="">
                <p class="text-center text-white h5">Loading Quotes...</p>
            </div>
        </Transition>

        <div class="my-5">
            <button v-if="!isLoading" @click.prevent="getRandomQuotes" class="btn btn-success">Generate Quotes!</button>
        </div>

    </div>

</template>

<script>
import axios from 'axios';

export default {
    name: "RandomQuotesComponent",
    data() {
        return {
            user: {},
            randomQuotes: [],
            favoriteQuotes: [],
            isLoading: false,
            isLoaded: false,
            hasFetchError: false,
        }
    },
    beforeRouteEnter(to, from, next) {
        next(vm => {
            vm.fetchFavoritesQuotes();;
        });
    },
    mounted() {
        this.user = JSON.parse(localStorage.getItem('user'));
        this.getRandomQuotes();

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
        fetchRandomQuotes() {
            this.randomQuotes = [];
            axios.get("/request/random_quotes/5")
                .then(response => {
                    this.randomQuotes = response.data.quotes;
                    setTimeout(() => {
                        this.isLoaded = true;
                    }, "500");

                }).catch(error => {
                    setTimeout(() => {
                        this.hasFetchError = true;
                    }, "500");

                }).finally(response => {
                    this.isLoading = false;

                });
        },
        getRandomQuotes() {
            this.fetchFavoritesQuotes();
            this.isLoaded = false;
            this.hasFetchError = false;
            setTimeout(() => {
                this.isLoading = true;
                this.fetchRandomQuotes();
            }, "500");        
        },
        handleQuoteInFavorite(quote, quoteId) {
            const isQuoteInFavorites = quoteId != null;
            const axiosConfig = {
                method: isQuoteInFavorites ? "delete" : "post",
                url: "/request/quote" + (isQuoteInFavorites ? "/" + quoteId : ""),
                data: {
                    quote: quote,
                    user_id: this.user.id
                }
            };
            axios(axiosConfig)
                .then(response => {
                    if (isQuoteInFavorites) {
                        // if is in favorites, then we have to remove it
                        this.favoriteQuotes = this.favoriteQuotes.filter(obj => obj.id !== response.data.id);
                    } else {
                        this.favoriteQuotes.push(response.data)
                    }
                }).catch(error => {
                    //
                });
        },
        findQuoteIdInFavorites(quote) {
            const favoriteQuote = this.favoriteQuotes.find(obj => obj.quote === quote);
            return (favoriteQuote != undefined) ? favoriteQuote.id : null;
        }
    }
}
</script>

<style>
.fade-enter-active,
.fade-leave-active {
    transition: opacity 0.5s ease;
}

.fade-enter-from,
.fade-leave-to {
    opacity: 0;
}
</style>
