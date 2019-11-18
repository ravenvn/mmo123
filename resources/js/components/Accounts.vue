<template>
    <div class="container-fluid">
        <account-form @reloadData="reloadData"></account-form>
        <div class="row">
            <div class="col">
                <b-card>
                    <b-table
                        id="accounts-table"
                        striped
                        hover
                        caption-top
                        :per-page="perPage"
                        :current-page="currentPage"

                        :items="accounts"
                        :fields="fields">
                        <template v-slot:table-caption><h2 class="text-center">Danh sách tài khoản</h2><b-button v-b-modal.modal-account-form variant="success" squared class="float-right"><i class="fa fa-plus"> Add</i></b-button></template>
                        <template v-slot:cell(index)="data">
                            {{ (currentPage - 1) * perPage + data.index + 1 }}
                        </template>
                        <template v-slot:cell(actions)="row">
                            <b-button-group>
                                <b-button variant="primary">Đăng nhập</b-button>
                                <b-button variant="success">Cookie</b-button>
                                <b-button variant="danger">Xóa</b-button>
                            </b-button-group>
                        </template>
                    </b-table>
                    <div class="float-right">
                        <b-pagination
                            v-model="currentPage"
                            :total-rows="rows"
                            :per-page="perPage"
                            aria-controls="accounts-table">
                        </b-pagination>
                    </div>
                </b-card>
            </div>
        </div>
    </div>
</template>

<script>
    import AccountForm from './AccountForm.vue'

    export default {
        components: {
            AccountForm
        },
        data() {
            return {
                fields: [
                    {
                        key: 'index',
                        label: '#'
                    },
                    {
                        key: 'email',
                        label: 'Email'
                    },
                    {
                        key: 'notes',
                        label: 'Ghi chú'
                    },
                    {
                        key: 'actions',
                        label: 'Tác vụ'
                    },
                    {
                        key: 'status',
                        label: 'Trạng thái'
                    },
                    {
                        key: 'detail_reason',
                        label: 'Nguyên nhân chi tiết'
                    },
                    {
                        key: 'updated_at',
                        label: 'Cập nhật gần nhất'
                    },
                ],
                accounts: [],
                perPage: 10,
                currentPage: 1    
            }
        },
        computed: {
            rows() {
                return this.accounts.length
            }
        },
        created() {
            this.loadAccounts()
        },
        methods: {
            async loadAccounts() {
                const response = await axios.get('/accounts')
                this.accounts = response.data.accounts
            },
            reloadData() {

            }
        }
    }
</script>
