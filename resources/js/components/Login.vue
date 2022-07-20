<template>
    <div>
        <div class="row justify-content-center mt-5">
            <div class="col-md-5">
                <div class="card">
                    <div class="card-header">
                        <h2>Login</h2>
                    </div>

                    <div class="card-body">
                        <b-overlay :show="overlay_show" rounded="sm">
                            <div class="mb-3">
                                <label for="type" class="form-label">User Type</label>
                                <select class="form-select" v-model="user.type" :class="{'border border-danger rounded' : errors && errors.type}">
                                    <option v-bind:key="index" v-for="(type,index) in types">{{ type }}</option>
                                </select>
                                <p v-if="errors && errors.type">
                                    <small>
                                        <span v-for="error in errors.type" class="text-danger"> 
                                            {{ error }}
                                        </span>
                                    </small>
                                </p>
                            </div>
                            <div class="mb-3">
                                <label for="email" class="form-label">Email</label>
                                <input type="email" class="form-control" v-model="user.email" :class="{'border border-danger rounded' : errors && errors.email}">
                                <p v-if="errors && errors.email">
                                    <small>
                                        <span v-for="error in errors.email" class="text-danger"> 
                                            {{ error }}
                                        </span>
                                    </small>
                                </p>
                            </div>
                            <div class="mb-3">
                                <label for="password" class="form-label">Password</label>
                                <input type="password" class="form-control" v-model="user.password" :class="{'border border-danger rounded' : errors && errors.password}">
                                <p v-if="errors && errors.password">
                                    <small>
                                        <span v-for="error in errors.password" class="text-danger"> 
                                            {{ error }}
                                        </span>
                                    </small>
                                </p>
                            </div>
                            <div class="d-grid gap-2">
                                <button class="btn btn-primary" @click="login">Sign in</button>
                            </div>
                            <div class="text-center">
                                <p>Not a member? <button class="btn btn-link" @click="$router.push('/register')">Register</button></p>
                            </div>
                        </b-overlay>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import Auth from '../Auth.js';      

    export default {
        name: 'login',
        data() {
            return {
                overlay_show : false,
                errors : false,
                types : ['HR','Team Leader'],
                user: {
                    type : '',
                    email: '',
                    password: '',
                }
            };
        },

        methods: {
            login() {
                this.overlay_show = true
                this.errors = false
                axios.post('http://127.0.0.1:8000/api/login', this.user)
                    .then(({data}) => {
                        Auth.login(data.access_token,data.user); //set local storage
                        window.location.href='/dashboard';
                    })
                    .catch((error) => {
                        this.errors = error.response.data.errors
                        this.overlay_show = false
                        console.log(error);
                    });
            }
        } 
    }
</script>