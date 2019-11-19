<template>
    <b-modal id="modal-account-edit-form" title="Sửa tài khoản" ref="accountEditForm">
        <b-form-group label="Email">
            <b-form-input v-model="account.email" placeholder="Nhập email..."></b-form-input>
        </b-form-group>
        <b-form-group label="Mật Khẩu">
            <b-form-input v-model="account.password" placeholder="Nhập mật khẩu..."></b-form-input>
        </b-form-group>
        <b-form-group label="Email khôi phục">
            <b-form-input v-model="account.recovery_email" placeholder="Nhập email khôi phục..."></b-form-input>
        </b-form-group>
        <b-form-group label="Số điện thoại">
            <b-form-input v-model="account.phone_number" placeholder="Nhập số điện thoại..."></b-form-input>
        </b-form-group>
        <b-form-group label="Ghi chú">
            <b-form-input v-model="account.notes" placeholder="Nhập ghi chú..."></b-form-input>
        </b-form-group>

        <div slot="modal-footer">
            <b-button squared variant="primary" @click="update" :disabled="account.email.trim() == '' || account.password.trim() == '' || account.recovery_email.trim() == ''"><i class="fa fa-floppy-o"></i> Cập nhật</b-button>
        </div>
    </b-modal>
</template>

<script>
    export default {
        props: {
            account: Object
        },
        methods: {
            async update() {
                const response = await axios.post('/accounts/update', {
                    id: this.account.id,
                    email: this.account.email.trim(),
                    password: this.account.password.trim(),
                    recovery_email: this.account.recovery_email.trim(),
                    phone_number: this.account.phone_number ? this.account.phone_number.trim() : null,
                    notes: this.account.notes ? this.account.notes.trim() : null
                })

                if (response.data.status == 'success') {
                    this.$bvToast.toast(`Đã cập nhật tài khoản thành công.`, {
                        title: 'Thành công',
                        autoHideDelay: 5000,
                        variant: 'success',
                        appendToast: true
                    })
                    this.$refs.accountEditForm.hide()
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
