<template>
    <div>
        <div class="container bg-white mt-4 p-4">
            <h1>Hi, <strong>{{user.name}}</strong> <small>({{user.type}})</small></h1>
        </div>
        <div v-if="user.type=='HR'">
            <candidateform></candidateform>
        </div>
        <div v-else>
            <candidatelist></candidatelist>
        </div>
    </div>
</template>
<script>
    import candidateform from './candidate/candidate_form.vue';
    import candidatelist from './candidate/candidate_list.vue';           
    export default {
        name: 'dashboard',
        components : { candidateform, candidatelist },
        data () {
            return {
                user: this.auth.user
            }
        },
        created() {
            // console.log(this.auth.user);
        }
    }
</script>