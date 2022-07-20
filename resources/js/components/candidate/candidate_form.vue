<template>
    <div class="container bg-white mt-4 p-4">
        <h3>Candidate Form</h3>
        <hr>
         <b-overlay :show="candidate_overlay_show" rounded="sm">
            <div v-if="show">
                <div class="mb-3">
                    <label for="name" class="form-label">Name</label>
                    <input type="text" class="form-control" v-model="candidate.name" :class="{'border border-danger rounded' : errors && errors.name}">
                    <p v-if="errors && errors.name">
                        <small>
                            <span v-for="error in errors.name" class="text-danger"> 
                                {{ error }}
                            </span>
                        </small>
                    </p>
                </div>
                <div class="mb-3">
                    <label for="type" class="form-label">Exprience</label>
                    <div class="row">
                        <div class="col">
                            <select class="form-select" v-model="candidate.exp_year" :class="{'border border-danger rounded' : errors && errors.exp_year}">
                                <option value="">---select years---</option>
                                <option v-bind:key="index" v-for="(n,index) in 25"  :value="n">{{ n }}</option>
                            </select>
                            <p v-if="errors && errors.exp_year">
                                <small>
                                    <span v-for="error in errors.exp_year" class="text-danger"> 
                                        {{ error }}
                                    </span>
                                </small>
                            </p>
                        </div>
                        <div class="col" >
                            <select class="form-select" v-model="candidate.exp_month" v-if="candidate.exp_year!='Fresher'"  :class="{'border border-danger rounded' : errors && errors.exp_month}">
                                <option value="">---select months---</option>
                                <option v-bind:key="index2" v-for="(m,index2) in 11" :value="m">{{ m }}</option>
                            </select>
                            <p v-if="errors && errors.exp_month">
                                <small>
                                    <span v-for="error in errors.exp_month" class="text-danger"> 
                                        {{ error }}
                                    </span>
                                </small>
                            </p>
                        </div>
                    </div>
                    
                </div>
                <div class="mb-3">
                    <label for="ctc" class="form-label">CTC</label>
                    <input type="number" class="form-control" v-model="candidate.ctc"  :class="{'border border-danger rounded' : errors && errors.ctc}">
                    <p v-if="errors && errors.ctc">
                        <small>
                            <span v-for="error in errors.ctc" class="text-danger"> 
                                {{ error }}
                            </span>
                        </small>
                    </p>
                </div>
                <div class="mb-3">
                    <label for="designation" class="form-label">Designation</label>
                    <select class="form-select" v-model="candidate.designation" :class="{'border border-danger rounded' : errors && errors.designation}">
                        <option v-bind:key="index" v-for="(designation,index) in designations">{{ designation }}</option>
                    </select>
                    <p v-if="errors && errors.designation">
                        <small>
                            <span v-for="error in errors.designation" class="text-danger"> 
                                {{ error }}
                            </span>
                        </small>
                    </p>
                </div>
                <div class="mb-3">
                    <label for="ctc" class="form-label">Expected CTC</label>
                    <input type="number" class="form-control" v-model="candidate.expected_ctc" :class="{'border border-danger rounded' : errors && errors.expected_ctc}">
                    <p v-if="errors && errors.expected_ctc">
                        <small>
                            <span v-for="error in errors.expected_ctc" class="text-danger"> 
                                {{ error }}
                            </span>
                        </small>
                    </p>
                </div>
                <div class="mb-3">
                    <label for="resume" class="form-label">Resume upload</label>
                    <input class="form-control" ref="photo" v-on:change="upload($event)" type="file" id="formFile" :class="{'border border-danger rounded' : errors && errors.resume}">
                    <p v-if="errors && errors.resume">
                        <small>
                            <span v-for="error in errors.resume" class="text-danger"> 
                                {{ error }}
                            </span>
                        </small>
                    </p>
                </div>

                <div class="mb-3">
                    <label for="assign" class="form-label">Assign Team Leader</label>
                    <select class="form-select" v-model="candidate.assign" :class="{'border border-danger rounded' : errors && errors.assign}">
                        <option v-bind:key="index" v-for="(leader,index) in team_leader_list" :value="leader.id">{{ leader.name }}</option>
                    </select>
                    <p v-if="errors && errors.assign">
                        <small>
                            <span v-for="error in errors.assign" class="text-danger"> 
                                {{ error }}
                            </span>
                        </small>
                    </p>
                </div>

                <button class="btn btn-primary" @click="submit">Submit</button>
            </div>
            <div v-else>
                 <h3>Candidate is sucessfully added.</h3>
            </div>
         </b-overlay>
    </div>
</template>
<script>
    export default {
        name: 'candidateform',
        data () {
            return {
                show : true,
                errors : false,
                candidate_overlay_show : true,
                user: this.auth.user,
                designations : ['Senior web developer','Web developer'],
                team_leader_list : [],
                candidate: {
                    name: '',
                    exp_year: '',
                    exp_month: '',
                    ctc: '',
                    designation: '',
                    expected_ctc: '',
                    resume: [],
                    assign:''
                }
            }
        },
        methods: {
            upload(e){
                this.candidate.resume = e.target.files[0]
            },
            getTeamLeader(){
                axios.get('http://127.0.0.1:8000/api/teamleader')
                  .then(response => {
                        this.team_leader_list = response.data.list
                        console.log("dfdfdf")
                        this.candidate_overlay_show = false
                        
                    })
                    .catch((error) => {
                        console.log(error);
                    });
            },
            submit(){
                this.errors = false
                this.candidate_overlay_show = true
                const formData = new FormData()
                formData.append('name', this.candidate.name)
                formData.append('exp_year', this.candidate.exp_year)
                formData.append('exp_month', this.candidate.exp_month)
                formData.append('ctc', this.candidate.ctc)
                formData.append('designation', this.candidate.designation)
                formData.append('expected_ctc', this.candidate.expected_ctc)
                formData.append('resume', this.candidate.resume)
                formData.append('assign', this.candidate.assign)

                axios.post('http://127.0.0.1:8000/api/candidate', formData)
                    .then(({data}) => {
                        console.log(data)
                        this.show = false
                        this.candidate_overlay_show = false
                    })
                    .catch((error) => {
                        this.errors = error.response.data.errors
                        this.candidate_overlay_show = false
                    });
            }
        },
        created() {
            this.getTeamLeader();
        }
    }
</script>