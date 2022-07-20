<template>
    <div>
        <nav class="navbar navbar-expand-lg navbar-dark bg-primary" v-if="loggedUser">
            <div class="container-fluid">
                <a class="navbar-brand" href="#">Goldmines Assignment</a>
                    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                        <span class="navbar-toggler-icon"></span>
                     </button>
                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                        <li class="nav-item">
                            <a class="nav-link active" aria-current="page" href="#">Dashboard</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link " href="javascript:void(0)" @click="logout()" tabindex="-1" aria-disabled="true">Logout</a>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>
        <router-view> </router-view>
    </div>
</template>

<script>
    import Auth from './Auth.js';
    export default {
        data() {
            return {
                loggedUser: this.auth.user
            };
        },
        mounted() {
            console.log(this.auth.user);
        },
        methods: {
            logout() {
                axios.post('http://127.0.0.1:8000/api/logout')
                .then(({data}) => {
                    Auth.logout(); //reset local storage
                     window.location.href='/login';
                })
                .catch((error) => {
                    console.log(error);
                });
            }
        }
    }
</script>
