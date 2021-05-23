<template>
    <div>


        <b-modal title="Измините данные группы" @ok="editGroup" @hidden="resetModal" centered v-model="modalShow">

            <div class="card-body py-0">


                            <div class="form-group row ">
                                <label class="col-sm-3">Наименование</label>
                                <div class="col-sm-8" style="margin-left: 13px;">
                                    <input-form
                                        style="font-size: 20px"
                                        v-model="groupField.name"
                                        name="name"
                                        @edit-field="editField"
                                        :gate="can.kassa_add_operation_type">

                                    </input-form>
                                </div>
                            </div>
                            <div class="form-group row ">
                                <label class="col-sm-3 col-form-label">Касса</label>
                                <div class="col-sm-9">
                                    <dynamic-select
                                        :options="branches"
                                        v-model="branch"
                                        option-value="id"
                                        option-text="name"
                                        placeholder="Введите для поиска кассы"/>
                                </div>
                            </div>


            </div>

            <template #modal-footer="{ ok, cancel, hide }">

                <b-button style= "margin-right: 250px" size="sm" variant="outline-secondary" @click="deleteField">
                    <b-icon icon="trash" ></b-icon> Удалить
                </b-button>

                <b-button size="sm" variant="success" @click="ok()">
                    Готово
                </b-button>

                <b-button size="sm" variant="danger" @click="cancel()">
                    Отмена
                </b-button>

            </template>

        </b-modal>



    </div>
</template>

<script>

import Vue from "vue";

export default {

    data() {
        return {
            branches: [],
            branch: '',
            group: '',
            groupField: '',
            group_name: '',
            can: '',
            operation_types: [],
            value: '',
            modalShow: false

        }

    },

    props: {
        can: {}
    },


    methods: {

        showModalEditGroupsKassaSettings(items) {
            this.groupField = items
            this.modalShow = true
            axios.get('api/v2/getbranches')
                .then(response => this.branches = response.data.data)
        },

        getBranches() {
            axios.get('api/v2/getbranches')
                .then(response => this.branches = response.data.data)
        },

        getAllGroups() {
            axios.get('api/v2/kassaGroups')
                .then(response => this.groups = response.data.data)
        },

        editGroup() {

if (this.branch) {
    axios.post('api/v2/kassaSettingsEditGroupsKassa', {
        field_id: this.groupField.id,
        field_value: this.branch.id,
        field_name: 'branch_id'
    })
    Vue.$toast.open({message: 'Группа обновлена', type: 'success', duration: 5000, position: 'top-right'});

    this.$emit('get-method')
    this.branch = ''
    this.group = ''
}

        },

        editField(e, name, type) {

            if (type) {
                axios.post('api/v2/kassaSettingsEditGroupsName', {field_id: this.groupField.id, field_name: name, field_value: e})
            } else {
                const value = e.target.value;
                const key = e.currentTarget.getAttribute('name');
                axios.post('api/v2/kassaSettingsEditGroupsName', {field_id: this.groupField.id, field_name: key, field_value: value})

            }
        },

        getOperationTypes() {
            axios.get('api/v2/operation_type')
                .then(response => {
                    this.operation_types = response.data.data, this.can = response.data.can
                })
        },

        deleteField(){
            axios.delete('api/v2/kassaGroups/' + this.groupField.id)
            Vue.$toast.open({message: 'Группа удалена' ,type: 'success',duration: 5000,position: 'top-right'});
            this.modalShow = false
            this.$emit('get-method')

        },

        resetModal(){
            this.branch = ''
        },

    }
}

</script>

<style scoped>

</style>

