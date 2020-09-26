<style scoped>
.invalid-feedback {
    display: block;
}
</style>
<template>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <b>Router Management</b>
                        <button class="btn btn-primary" @click="openCreateModal()">Create</button>
                    </div>

                    <div class="card-body">
                        <table class="table table-hover">
                            <thead>
                            <tr>
                                <th>SAP Id</th>
                                <th>Hostname</th>
                                <th>MAC Address</th>
                                <th>Actions</th>
                            </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td><input type="text" class="form-control" v-model="search.sap_id" placeholder="SAP Id"></td>
                                    <td><input type="text" class="form-control" v-model="search.hostname" placeholder="Hostname"></td>
                                    <td><input type="text" class="form-control" v-model="search.mac_address" placeholder="MAC Address"></td>
                                    <td><button class="btn btn-info" @click="getRouterList()">Search</button></td>
                                </tr>
                                <tr v-for="router in routers" :key="router.id">
                                    <td>{{router.sap_id}}</td>
                                    <td>{{router.hostname}}</td>
                                    <td>{{router.mac_address}}</td>
                                    <td>
                                        <button class="btn btn-primary" @click="openEditModal(router)">Edit</button>
                                        <button class="btn btn-danger" @click="deleteRouter(router)">Delete</button>
                                    </td>
                                </tr>
                                <tr v-if="routers.length == 0">
                                    <td colspan="4" style="text-align:center;">
                                        No records
                                    </td>
                                </tr>                            
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal" v-show="showModal" style="display:block;">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" v-text="currentRouter.id ? 'Edit Router' : 'Create Router'">Router</h5>
                    <button type="button" class="close" data-dismiss="modal" @click="closeModal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group row">
                        <label for="sap_id" class="col-md-4 col-form-label text-md-right">SAP Id</label>
                        <div class="col-md-6">
                            <input id="sap_id" type="text" v-model="currentRouter.sap_id" class="form-control" maxlength="24" required autofocus>
                            <span class="invalid-feedback" role="alert" v-if="validationErrors.sap_id">
                                <strong>{{ validationErrors.sap_id[0] }}</strong>
                            </span>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="hostname" class="col-md-4 col-form-label text-md-right">Hostname</label>
                        <div class="col-md-6">
                            <input id="hostname" type="text" v-model="currentRouter.hostname" class="form-control" maxlength="15" required autofocus>
                            <span class="invalid-feedback" role="alert" v-if="validationErrors.hostname">
                                <strong>{{ validationErrors.hostname[0] }}</strong>
                            </span>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="mac_address" class="col-md-4 col-form-label text-md-right">MAC Address</label>
                        <div class="col-md-6">
                            <input id="mac_address" type="text" v-model="currentRouter.mac_address" class="form-control" maxlength="17" required autofocus>
                            <span class="invalid-feedback" role="alert" v-if="validationErrors.mac_address">
                                <strong>{{ validationErrors.mac_address[0] }}</strong>
                            </span>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" v-if="currentRouter.id" @click="updateRouter">Save changes</button>
                    <button type="button" class="btn btn-primary" v-if="!currentRouter.id" @click="createRouter">Save changes</button>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal" @click="closeModal">Close</button>
                </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        mounted() {
            this.getRouterList();
        },
        data() {
            return {
                routers: [],
                currentRouter: {},
                showModal: false,
                validationErrors: {},
                search: {}
            }
        },
        methods: {

            closeModal() {
                this.validationErrors = {};
                this.showModal = false;
            },

            openEditModal(router) {
                this.currentRouter = router;
                this.showModal = true;
            },

            openCreateModal(router) {
                this.currentRouter = {};
                this.showModal = true;
            },

            getRouterList() {

                axios.get("/api/routers", { params: this.search })
                .then(({ data }) => {
                    this.routers = data.data;
                });

            },

            deleteRouter(router) {
                
                axios.delete("/api/routers/" + router.hostname)
                .then(({ data }) => {
                    if(data.status == 1) {
                        this.routers.splice(this.routers.indexOf(router), 1);
                        this.$toastr.s(data.message);
                    } else {
                        this.$toastr.e(data.message);
                    }
                });

            },

            createRouter() {                
                this.validationErrors = {};
                axios.post("/api/routers/", this.currentRouter)
                .then(({ data }) => {
                    if(data.status == 1) {
                        this.routers.push(this.currentRouter);
                        this.$toastr.s(data.message);
                    } else {
                        this.$toastr.e(data.message);
                    }

                    this.showModal = false;
                }).catch(error => {
                    if (error.response.status == 422){
                        this.validationErrors = error.response.data.errors;
                    }
                });

            },

            updateRouter() {
                this.validationErrors = {};
                axios.post("/api/routers/" + this.currentRouter.hostname, this.currentRouter)
                .then(({ data }) => {
                    if(data.status == 1) {
                        let index = this.routers.indexOf(this.currentRouter);
                        this.currentRouter[index] = this.currentRouter;
                        this.$toastr.s(data.message);
                    } else {
                        this.$toastr.e(data.message);
                    }

                    this.showModal = false;
                }).catch(error => {
                    if (error.response.status == 422){
                        this.validationErrors = error.response.data.errors;
                    }
                });

            }
        }
    }
</script>
