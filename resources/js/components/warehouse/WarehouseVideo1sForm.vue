<template>
    <section>
        <b-form-group label="Tên kho">
            <b-form-input v-model="name" placeholder="Nhập tên kho..."></b-form-input>
        </b-form-group>
        <b-form-group label="Danh sách video ID 1s:">
            <b-form-textarea
                id="textarea"
                v-model="videoIds"
                placeholder="Nhập danh sách video ID, mỗi ID một hàng"
                rows="5"
                max-rows="10"
            ></b-form-textarea>
        </b-form-group>
        <b-form-group class="float-right">
            <b-button squared variant="primary" @click="save" :disabled="name.trim() == '' || videoIds.trim() == ''"><i class="fa fa-floppy-o"></i> Lưu</b-button>
        </b-form-group>
    </section>
</template>

<script>
    export default {
        props: ['warehouse'],
        data() {
            return {
                name: this.warehouse ? this.warehouse.name : '',
                videoIds: this.warehouse ?this.warehouse.videos.map(x => x.video_id).join('\n') : '',
            }
        },
        methods: {
            async save() {
                if (this.warehouse != null) {
                    const response = await axios.post('/warehouses/update-warehouse-video-1s', {
                        id: this.warehouse.id,
                        name: this.name,
                        videoIds: this.videoIds
                    })

                    if (response.data.status == 'success') {
                        this.$bvToast.toast(`Đã lưu thành công.`, {
                        title: 'Thành công',
                        autoHideDelay: 5000,
                        variant: 'success',
                        appendToast: true
                        })
                        this.$bvModal.hide('modal-warehouse-video1s-form')
                        this.$emit('reloadData')
                    } else {
                        this.$bvToast.toast(`Lỗi: ${response.data.message}.`, {
                        title: 'Lỗi',
                        autoHideDelay: 5000,
                        variant: 'danger',
                        appendToast: true
                        })
                    }
                } else {
                    const response = await axios.post('/warehouses/store-warehouse-video-1s', {
                        name: this.name,
                        videoIds: this.videoIds
                    })

                    if (response.data.status == 'success') {
                        this.$bvToast.toast(`Đã lưu thành công.`, {
                        title: 'Thành công',
                        autoHideDelay: 5000,
                        variant: 'success',
                        appendToast: true
                        })
                        this.$bvModal.hide('modal-warehouse-video1s-form')
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
    }
</script>
