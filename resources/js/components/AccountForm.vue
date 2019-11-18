<template>
    <b-modal id="modal-account-form" title="Thêm tài khoản mới" ref="accountForm">
        <b-form-group label="Danh sách tài khoản:">
            <b-form-textarea
                id="textarea"
                v-model="accounts"
                placeholder="Email|Mật khẩu|Email khôi phục|Ghi_chú. Chấp nhận các ký tự phân cách sau: cách trống, tab, -, |, ;, : và dấu ,"
                rows="5"
                max-rows="10"
            ></b-form-textarea>
        </b-form-group>
        <b-form-group label="Ghi chú">
            <b-form-input v-model="notes" placeholder="Nhập ghi chú..."></b-form-input>
        </b-form-group>

        <div slot="modal-footer">
            <b-button squared variant="primary" @click="save" :disabled="accounts.trim() == ''"><i class="fa fa-floppy-o"></i> Lưu</b-button>
        </div>
    </b-modal>
</template>

<script>
    export default {
        data() {
            return {
                accounts: '',
                notes: ''
            }
        },
        methods: {
            async save() {
                const response = await axios.post('/accounts/store', {
                    accounts: this.accounts,
                    notes: this.notes
                })

                if (response.data.status == 'success') {
                    this.$bvToast.toast(`Đã lưu ${response.data.numberInserted} tài khoản.`, {
                    title: 'Thành công',
                    autoHideDelay: 5000,
                    variant: 'success',
                    appendToast: true
                    })
                    this.$refs.accountForm.hide()
                    this.$emit('reloadData')
                } else {
                    this.$bvToast.toast(`Lỗi: ${response.data.message}.`, {
                    title: 'Lỗi',
                    autoHideDelay: 5000,
                    variant: 'danger',
                    appendToast: true
                    })
                }
            }            
        }
    }
</script>
