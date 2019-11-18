<template>
    <div class="container-fluid">
        <account-form @reloadData="reloadData"></account-form>
        <account-edit-form @reloadData="reloadData" :account="currentAccount" v-if="currentAccount"></account-edit-form>
        <b-modal id="modal-confirm-delete" title="Xác nhận">
            <p class="my-4">Bạn chắc chắn muốn xóa chứ?</p>
            <div slot="modal-footer">
                <b-button squared variant="danger" @click="deleteContact"><i class="fa fa-trash"></i> Xóa</b-button>
            </div>
        </b-modal>
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
                        <template v-slot:table-caption><h2 class="text-center">Danh sách tài khoản</h2><b-button v-b-modal.modal-account-form variant="success" squared class="float-right"><i class="fa fa-plus"> Thêm mới</i></b-button></template>
                        <template v-slot:cell(index)="data">
                            {{ (currentPage - 1) * perPage + data.index + 1 }}
                        </template>
                        <template v-slot:cell(actions)="row">
                            <b-button-group>
                                <b-button squared variant="primary" @click="loginGmail(row.item)">Đăng nhập</b-button>
                                <b-button squared variant="success">Cookie</b-button>
                                <b-button squared variant="warning" @click="showEditForm(row.item)">Sửa</b-button>
                                <b-button squared variant="danger" @click="showDeleteConfirm(row.item)">Xóa</b-button>
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
    import AccountEditForm from './AccountEditForm.vue'

    export default {
        components: {
            AccountForm,
            AccountEditForm
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
                currentPage: 1,
                currentAccount: null
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
                this.loadAccounts()
            },
            showEditForm(account) {
                this.currentAccount = account
                this.$bvModal.show('modal-account-edit-form')
            },
            showDeleteConfirm(account) {
                this.currentAccount = account
                this.$bvModal.show('modal-confirm-delete')
            },
            async deleteContact() {
                const response = await axios.post('/accounts/delete', {
                    id: this.currentAccount.id
                })

                if (response.data.status == 'success') {
                    this.$bvToast.toast(`Đã xóa thành công tài khoản.`, {
                        title: 'Thành công',
                        autoHideDelay: 5000,
                        variant: 'success',
                        appendToast: true
                    })
                    this.$bvModal.hide('modal-confirm-delete')
                    this.reloadData()
                } else {
                    this.$bvToast.toast(`Lỗi: ${response.data.message}.`, {
                        title: 'Lỗi',
                        autoHideDelay: 5000,
                        variant: 'danger',
                        appendToast: true
                    })
                }
            },
            async loginGmail(account) {
                const response = await axios.get('http://localhost:8080/login-gmail', {
                    params: {
                        email: account.email,
                        password: account.password,
                        recovery_email: account.recovery_email
                    }
                })
                if (response.data.status == 'success') {
                    this.$bvToast.toast(`Đã đăng nhập công tài khoản.`, {
                        title: 'Thành công',
                        autoHideDelay: 5000,
                        variant: 'success',
                        appendToast: true
                    })
                    this.updateStatus(true)
                } else {
                    this.$bvToast.toast(`Lỗi: ${response.data.message}.`, {
                        title: 'Đăng nhập lỗi',
                        autoHideDelay: 5000,
                        variant: 'danger',
                        appendToast: true
                    })
                    this.updateStatus(false, response.data.errorType)
                }
                this.reloadData()
            },
            async updateStatus(status, errorType = null) {

            }
        }
    }
</script>
