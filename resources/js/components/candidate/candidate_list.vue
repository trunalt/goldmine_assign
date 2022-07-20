<template>
    <div class="container bg-white mt-4 p-4">
        <h3>Candidate List</h3>
        <hr>
        <div>
            
            <!-- Main table element -->
        <b-overlay :show="overlay_show" rounded="sm">
            <b-table
            :items="items"
            :fields="fields"
            :sort-by.sync="sortBy"
            :sort-desc.sync="sortDesc"
            :sort-direction="sortDirection"
            stacked="md"
            show-empty
            small
            @filtered="onFiltered"
            >
            
            <template #cell(actions)="row">
                <b-button size="sm" @click="info(row.item, row.index, $event.target)" class="mr-1">
                Edit
                </b-button>
            </template>

                <template #cell(name)="row">
                    {{ row.item.name }} <small>({{ row.item.designation }})</small>
                </template>

                <template #cell(exp_year)="row">
                    {{ row.item.exp_year }} years {{ row.item.exp_month }} months 
                </template>

                <template #cell(created_at)="row">
                    {{ row.item.created_at | date }}
                </template>

                <template #cell(resume)="row">
                    <a :href="'/resume/'+row.item.id+'/'+row.item.resume" target="_blank">{{ row.item.resume }}</a>
                </template>

            </b-table>
            </b-overlay>

            <!-- Info modal -->
            <b-modal :id="infoModal.id" :title="infoModal.name" ok-only @hide="resetInfoModal">
                <b-overlay :show="infoModal_overlay_show" rounded="sm">   
                    <div class="mb-3">
                        <label for="name" class="form-label">Feedback</label>
                        <textarea class="form-control" v-model="form.feedback"></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="type" class="form-label">Status</label>
                        <select class="form-select" v-model="form.status">
                            <option v-bind:key="index" v-for="(n,index) in status" :value="n">{{ n }}</option>
                        </select>
                    </div>
                </b-overlay>
                <span v-if="msg">{{ msg }}</span>
                
                <template #modal-footer="{ cancel }">
                    <b-button size="sm" variant="primary" @click="submit()">
                        Update
                    </b-button>
                    <b-button size="sm" variant="secondary" @click="cancel()">
                        Cancel
                    </b-button>
                </template>
            
            </b-modal>
  
        </div>
    </div>
</template>
<script>
import moment from 'moment'

  export default {
    data() {
      return {
        overlay_show : true,
        infoModal_overlay_show : false,
        user: this.auth.user,
        status : ['Approved','Rejected'],
        items: [],
        msg : false,
        form : {
            id : '',
            feedback : '',
            status : '',
            assign : ''
        },
        fields: [
          { key: 'id', label: 'ID', sortable: false },
          { key: 'created_at', label: 'Date', sortable: false },
          { key: 'name', label: 'Name', sortable: false },
          { key: 'exp_year', label: 'experience', sortable: false },
          { key: 'ctc', label: 'CTC', sortable: false, class: 'text-center' },
          { key: 'expected_ctc', label: 'Excepted CTC', sortable: false, class: 'text-center' },
          { key: 'resume', label: 'Download Resume', sortable: false, class: 'text-center' },
          { key: 'status', label: 'Status', sortable: false, class: 'text-center' },
          { key: 'actions', label: 'Actions' }
        ],
        infoModal: {
          id: 'info-modal',
          title: '',
          content: '',
          name : ''
        }
      }
    },
    filters: {
        date(val) {
           return moment(val).format("DD-MMM-YYYY")
        }
    }, 
    methods: {
        getCandidate(){
            const formData = new FormData()
            formData.append('id', this.user.id)
                axios.post('http://127.0.0.1:8000/api/candidate/list', formData)
                        .then(response => {
                            this.items = response.data.list;
                            this.overlay_show = false
                        })
        },
        submit(){
            this.msg = false
            this.infoModal_overlay_show = true
            const formData = new FormData()
            formData.append('id', this.form.id)
            formData.append('assign', this.form.assign)
            formData.append('feedback', this.form.feedback)
            formData.append('status', this.form.status)
            axios.post('http://127.0.0.1:8000/api/candidate/feedback', formData)
                    .then(response => {
                        this.items = response.data.list
                        this.msg = response.data.message
                        this.infoModal_overlay_show = false
                    })
        },
        exportTo(id) {
            const formData = new FormData()
            formData.append('id', id)
            axios.post('http://127.0.0.1:8000/api/candidate/resume/download', formData,
            {
                responseType: 'blob',
                Accept: 'application/pdf',
            })
            .then((response) => {
                const url = window.URL.createObjectURL(new Blob([response.data], {type: 'application/pdf'}));
                const link = document.createElement('a');
                console.log(link);
                link.href = url;
                link.setAttribute('download', 'paymentReport.pdf'); //or any other extension
                document.body.appendChild(link);
                link.click()
            })
            .catch((error) => {
              
            })
        },
      info(item, index, button) {
        this.msg = false
        this.infoModal.title = `Row index: ${index}`
        this.infoModal.name = item.name + '('+ item.designation + ')'
        this.infoModal.content = JSON.stringify(item, null, 2)
        this.form.id = item.id
        this.form.assign = item.assign
        this.form.status = item.status
        this.form.feedback = item.feedback
        this.$root.$emit('bv::show::modal', this.infoModal.id, button)
      },
      resetInfoModal() {
        this.infoModal.title = ''
        this.infoModal.content = ''
      }
    },
    created(){
        this.getCandidate();
    }
  }
</script>
