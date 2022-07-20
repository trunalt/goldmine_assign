<template>
    <div>
        <div class="row justify-content-center mt-5">
            <div class="col-md-5">
                <div class="card">
                    <div class="card-header">
                        <h2>Register</h2>
                        <span v-if="msg">{{ msg }}</span>
                    </div>

                    <div class="card-body">
                        <b-overlay :show="overlay_show" rounded="sm">
                            <div class="mb-3">
                                <label for="name" class="form-label">Name</label>
                                <input type="text" class="form-control" v-model="user.name" :class="{'border border-danger rounded' : errors && errors.name}">
                                <p v-if="errors && errors.name">
                                    <small>
                                        <span v-for="error in errors.name" class="text-danger"> 
                                            {{ error }}
                                        </span>
                                    </small>
                                </p>
                            </div>
                            <div class="mb-3">
                                <label for="type" class="form-label">User Type</label>
                                <select class="form-select" v-model="user.type" :class="{'border border-danger rounded' : errors && errors.type}">
                                    <option v-bind:key="index" v-for="(type,index) in types" :value="type">{{ type }}</option>
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
                            <div class="mb-3">
                                <label for="password" class="form-label">Confirm Password</label>
                                <input type="password" class="form-control" v-model="user.password_confirmation" :class="{'border border-danger rounded' : errors && errors.password_confirmation}">
                                <p v-if="errors && errors.password_confirmation">
                                    <small>
                                        <span v-for="error in errors.password_confirmation" class="text-danger"> 
                                            {{ error }}
                                        </span>
                                    </small>
                                </p>
                            </div>
                            <button class="btn btn-primary" @click="register">Register</button>
                            <button class="btn btn-link" @click="$router.push('/login')">Back to login</button>
                        </b-overlay>
                    </div>

                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
         name: 'register',
        data() {
            return {
                errors : false,
                overlay_show : false,
                types : ['HR','Team Leader'],
                msg :false,
                user: {
                    name: '',
                    type: '',
                    email: '',
                    password: '',
                    password_confirmation: ''
                }
            };
        },

        methods: {
            register() {
                this.errors =false
                this.msg = false
                this.overlay_show = true
                console.log(this.user);
                axios.post('http://127.0.0.1:8000/api/register', this.user)
                    .then(({data}) => {
                        //this.$router.push('/login');
                        this.msg = data.message
                        window.location.href='/login';
                    })
                    .catch((error) => {
                        this.errors = error.response.data.errors
                        this.overlay_show = false
                        this.msg = false
                        console.log(error);
                    });
            }
        }
    }
</script>