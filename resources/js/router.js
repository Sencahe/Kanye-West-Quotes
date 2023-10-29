import { createWebHistory, createRouter } from "vue-router";
import axios from "axios";
import IndexComponent from "./components/IndexComponent.vue"
import LoginComponent from "./components/LoginComponent.vue"
import RegisterComponent from "./components/RegisterComponent.vue"
import QuotesComponent from "./components/QuotesComponent.vue"
import QuotesRandomComponent from "./components/QuotesRandomComponent.vue"
import QuotesFavoriteComponent from "./components/QuotesFavoriteComponent.vue"
import NotFound from "./components/NotFound.vue"


const routes = [
    { path: "/", name: "Index", component: IndexComponent, children:[
        {path:"/", name:"Login",component: LoginComponent},
        {path:"/register", name:"Register",component: RegisterComponent},
    ]},
    { path: "/quotes", name: "Quotes", component: QuotesComponent, meta: { requiresAuth: true }, children:[
        {path:"/quotes", name:"QuotesRandom",component: QuotesRandomComponent},
        {path:"/quotes/favorites", name:"QuotesFavorite",component: QuotesFavoriteComponent}

    ]},
    { path: "/:pathMatch(.*)*", component: NotFound }
];

const router = createRouter({
    history: createWebHistory(),
    routes,
});

router.beforeEach((to, from, next) => {
    if (to.matched.some(record => record.meta.requiresAuth)) {
        axios.get('/auth/session')
            .then(() => {
                next();
            })
            .catch(() => {
                next('/');
            });

    } else {
        // Proceed to the requested route
        next();
    }
});




export default router;
