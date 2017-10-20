<template>
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <button @click="initAddLink()" class="btn btn-primary btn-xs pull-right">
                            + Add New Link
                        </button>
                        My Links
                    </div>

                    <div class="panel-body">
                        <table class="table table-bordered table-striped table-responsive" v-if="links.length > 0">
                            <tbody>
                            <tr>
                                <th>
                                    No.
                                </th>
                                <th>
                                    Url
                                </th>
                                <th>
                                    Hash
                                </th>
                                <th>
                                    Clicks
                                </th>
                                <th>
                                    Action
                                </th>
                            </tr>
                            <tr v-for="(link, index) in links">
                                <td>{{ index + 1 }}</td>
                                <td>
                                    {{ link.url }}
                                </td>
                                <td>
                                    <a target="_blank" :href="u + link.hash">
                                    {{ link.hash }}
                                    </a>
                                </td>
                                <td>
                                    {{ link.clicks }}
                                </td>
                                <td>
                                    <button @click="initUpdate(index)" class="btn btn-success btn-xs">Edit</button>
                                    <button @click="deleteLink(index)" class="btn btn-danger btn-xs">Delete</button>
                                </td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade" tabindex="-1" role="dialog" id="add_link_model">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                                aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title">Add New Link</h4>
                    </div>
                    <div class="modal-body">

                        <div class="alert alert-danger" v-if="errors.length > 0">
                            <ul>
                                <li v-for="error in errors">{{ error }}</li>
                            </ul>
                        </div>

                        <div class="form-group">
                            <label for="url">Url:</label>
                            <input type="text" name="url" id="url" placeholder="Link url" class="form-control"
                                   v-model="link.url">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                        <button type="button" @click="createLink" class="btn btn-primary">Submit</button>
                    </div>
                </div><!-- /.modal-content -->
            </div><!-- /.modal-dialog -->
        </div><!-- /.modal -->

        <div class="modal fade" tabindex="-1" role="dialog" id="update_link_model">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                                aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title">Update Link</h4>
                    </div>
                    <div class="modal-body">

                        <div class="alert alert-danger" v-if="errors.length > 0">
                            <ul>
                                <li v-for="error in errors">{{ error }}</li>
                            </ul>
                        </div>

                        <div class="form-group">
                            <label>Url:</label>
                            <input type="text" placeholder="Link URL" class="form-control"
                                   v-model="update_link.url">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                        <button type="button" @click="updateLink" class="btn btn-primary">Submit</button>
                    </div>
                </div><!-- /.modal-content -->
            </div><!-- /.modal-dialog -->
        </div><!-- /.modal -->

    </div>
</template>

<script>
    export default {
        data() {
            return {
                link: {
                    url: '',
                    hash: ''
                },
                errors: [],
                links: [],
                update_link: {},
                u: '/r/'
            }
        },
        mounted() {
            this.readLinks();
        },
        methods: {
            initAddLink() {
                $("#add_link_model").modal("show");
            },
            createLink() {
                axios.post('/link', {
                    url: this.link.url
                })
                    .then(response => {

                        this.reset();

                        this.links.push(response.data.link);

                        $("#add_link_model").modal("hide");

                    })
                    .catch(error => {
                        this.errors = [];
                        if (error.response.data.errors.url) {
                            this.errors.push(error.response.data.errors.url[0]);
                        }

                        if (error.response.data.errors.hash) {
                            this.errors.push(error.response.data.errors.hash[0]);
                        }
                    });
            },
            reset() {
                this.link.url = '';
                this.link.hash = '';
            },
            readLinks() {
                axios.get('/link')
                    .then(response => {
                        this.links = response.data.links;
                    });
            },
            initUpdate(index) {
                this.errors = [];
                $("#update_link_model").modal("show");
                this.update_link = this.links[index];
            },
            updateLink() {
                axios.patch('/link/' + this.update_link.id, {
                    url: this.update_link.url
                })
                    .then(response => {
                        $("#update_link_model").modal("hide");
                        this.readLinks();
                    })
                    .catch(error => {
                        this.errors = [];
                        if (error.response.data.errors.url) {
                            this.errors.push(error.response.data.errors.url[0]);
                        }

                        if (error.response.data.errors.hash) {
                            this.errors.push(error.response.data.errors.hash[0]);
                        }
                    });
            },
            deleteLink(index) {
                let conf = confirm("Do you ready want to delete this link?");
                if (conf === true) {

                    axios.delete('/link/' + this.links[index].id)
                        .then(response => {
                            this.links.splice(index, 1);
                        })
                        .catch(error => {
                        });
                }
            }
        }
    }
</script>