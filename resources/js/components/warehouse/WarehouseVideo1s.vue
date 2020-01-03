<template>
    <div class="container-fluid">
        <b-modal id="modal-confirm-delete" title="Xác nhận">
            <p class="my-4">Bạn chắc chắn muốn xóa chứ?</p>
            <div slot="modal-footer">
                <b-button squared variant="danger" @click="deleteWarehouse"><i class="fa fa-trash"></i> Xóa</b-button>
            </div>
        </b-modal>
        <b-modal id="modal-confirm-mass-delete" title="Xác nhận">
            <p class="my-4">Bạn chắc chắn muốn xóa chứ?</p>
            <div slot="modal-footer">
                <b-button squared variant="danger" @click="deleteWarehouses"><i class="fa fa-trash"></i> Xóa</b-button>
            </div>
        </b-modal>
        <b-modal id="modal-warehouse-video1s-form" :title="currentWarehouse == null ? 'Thêm kho mới' : 'Sửa kho'">
            <warehouse-video1s-form @reloadData="reloadData" :warehouse="currentWarehouse"></warehouse-video1s-form>
            <div slot="modal-footer"></div>
        </b-modal>
        <div class="row">
            <div class="col">
                <b-card>
                    <b-table
                        id="video1s-table"
                        ref="video1sTable"
                        hover
                        bordered
                        caption-top
                        responsive
                        selectable
                        :per-page="perPage"
                        :current-page="currentPage"

                        @row-selected="onRowSelected"
                        :items="warehouses"
                        :fields="fields"
                        >
                        <template v-slot:table-caption>
                            <h2 class="text-center">Kho video 1s</h2>
                            <div class="col">
                                <b-button @click="selectAllRows" squared variant="primary">All</b-button>
                                <b-button @click="clearSelected" squared variant="secondary">Clear</b-button>
                                <b-button variant="danger" squared @click="showMassDeleteConfirm()" :disabled="selectedWarehouses.length == 0"><i class="fa fa-trash"> Xóa kho video</i></b-button>
                                <b-button variant="success" squared class="float-right" @click="showNewForm()"><i class="fa fa-plus"> Thêm mới</i></b-button>
                            </div>
                        </template>
                        <template v-slot:cell(selected)="{ rowSelected }">
                            <template v-if="rowSelected">
                                <span>&check;</span>
                            </template>
                            <template v-else>
                                <span>&nbsp;</span>
                            </template>
                        </template>
                        <template v-slot:cell(index)="row">
                            {{ (currentPage - 1) * perPage + row.index + 1 }}
                        </template>
                        <template v-slot:cell(videoids)="row">
                            <ol>
                                <li v-for="video in row.item.videos" :key="video.id">{{ video.video_id }}</li>
                            </ol>
                        </template>
                        <template v-slot:cell(actions)="row">
                            <b-button-group>
                                <b-button squared variant="warning" @click="showEditForm(row.item)" title="Sửa"><i class="fa fa-pencil"></i></b-button>
                                <b-button squared variant="danger" @click="showDeleteConfirm(row.item)" title="Xóa"><i class="fa fa-trash"></i></b-button>
                            </b-button-group>
                        </template>
                    </b-table>
                    <div class="float-right">
                        <b-pagination
                            v-model="currentPage"
                            :total-rows="rows"
                            :per-page="perPage"
                            aria-controls="warehouses-table">
                        </b-pagination>
                    </div>
                </b-card>
            </div>
        </div>
    </div>
</template>

<script>
    import WarehouseVideo1sForm from './WarehouseVideo1sForm.vue'

    export default {
        components: {
            WarehouseVideo1sForm,
        },
        data() {
            return {
                fields: [
                    {
                        key: 'selected',
                        label: 'Chọn'
                    },{
                        key: 'index',
                        label: '#'
                    },
                    {
                        key: 'name',
                        label: 'Tên kho'
                    },
                    {
                        key: 'videoIds',
                        label: 'Danh sách Video ID'
                    },
                    {
                        key: 'actions',
                        label: 'Tác vụ'
                    },
                ],
                warehouses: [],
                perPage: 10,
                currentPage: 1,
                currentWarehouse: null,
                selectedWarehouses: []
            }
        },
        computed: {
            rows() {
                return this.warehouses.length
            }
        },
        created() {
            this.loadWarehouses()
        },
        methods: {
            async loadWarehouses() {
                const response = await axios.get('/warehouses/get-warehouse-video-1s')
                this.warehouses = response.data.warehouses
            },
            reloadData() {
                this.loadWarehouses()
            },
            showNewForm() {
                this.currentWarehouse = null
                this.$bvModal.show('modal-warehouse-video1s-form')
            },
            showEditForm(warehouse) {
                this.currentWarehouse = warehouse
                this.$bvModal.show('modal-warehouse-video1s-form')
            },
            showDeleteConfirm(warehouse) {
                this.currentWarehouse = warehouse
                this.$bvModal.show('modal-confirm-delete')
            },
            selectAllRows() {
                this.$refs.video1sTable.selectAllRows()
            },
            clearSelected() {
                this.$refs.video1sTable.clearSelected()
            },
            onRowSelected(items) {
                this.selectedWarehouses = items
            },
            showMassDeleteConfirm() {
                this.$bvModal.show('modal-confirm-mass-delete')
            },
            async deleteWarehouses() {
                const response = await axios.post('/warehouses/mass-delete-warehouse-video-1s', {
                    ids: this.selectedWarehouses.map(warehouse => warehouse.id)
                })

                if (response.data.status == 'success') {
                    this.$bvToast.toast(`Đã xóa thành công các tài khoản.`, {
                        title: 'Thành công',
                        autoHideDelay: 5000,
                        variant: 'success',
                        appendToast: true
                    })
                    this.$bvModal.hide('modal-confirm-mass-delete')
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
            async deleteWarehouse() {
                const response = await axios.post('/warehouses/delete-warehouse-video-1s', {
                    id: this.currentWarehouse.id
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
        }
    }
</script>
