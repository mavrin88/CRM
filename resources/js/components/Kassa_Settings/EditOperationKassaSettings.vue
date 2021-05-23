<template>
    <div>
        <b-modal title="Измините данные Операции" @ok="editOperation" @hidden="resetModal" centered ok-only ok-title="Готово" v-model="modalShow">

            <div class="radio-group-toggle mb-5 text-center">
                <input class="mx-3" type="checkbox" id="comin" value="1" v-model="dataObject.comin">
                <label for="comin">Приход</label>
                <input class="mx-3" type="checkbox" id="out" value="1" v-model="dataObject.out">
                <label for="out">Расход</label>
                <input class="mx-3" type="checkbox" id="cash" value="1" v-model="dataObject.cash">
                <label for="cash">Наличные</label>
                <input class="mx-3" type="checkbox" id="beznal" value="1" v-model="dataObject.beznal">
                <label for="beznal">Экваринг</label>


            </div>

            <div class="card-body py-0">

                    <div class="form-group row ">
                        <label class="col-sm-3">Название</label>
                        <div class="col-sm-8 " style="margin-left: 13px;">
                            <input-form
                                v-model="operationField.name"
                                name="name"
                                @edit-field="editField"
                                :gate="can.kassa_add_operation_type">

                            </input-form>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-3 col-form-label">Касса</label>
                        <div class="col-sm-9">
                            <dynamic-select
                                :options="branches"
                                v-model="dataObject.branch"
                                option-value="id"
                                option-text="name"
                                placeholder="Введите для поиска кассы"/>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-sm-3 col-form-label">Группа</label>
                        <div class="col-sm-9">
                            <dynamic-select
                                :options="group"
                                v-model="dataObject.groupid"
                                option-value="id"
                                option-text="name"
                                placeholder="Введите для поиска группы"/>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-sm-3 col-form-label">Примечание</label>
                        <div class="col-sm-8" style="margin-left: 13px;">
                            <textarea
                                class="form-control"
                                v-model="dataObject.coment"
                                @blur="event => editField(event, 'coment')"
                                rows="3"
                                name="coment"
                            >
                                  </textarea>
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
            groupid: '',
            operationField: '',
            can: '',
            comment: '',
            operation_types: [],
            modalShow: false,
            dataObject: {},

        }

    },

    props: {
        can: {}
    },

    methods: {

        showModalEditOperationKassaSettings(items) {
            this.operationField = items
            this.modalShow = true
            axios.get('api/v2/getbranches')
                .then(response => this.branches = response.data.data)
            axios.get('api/v2/kassaGroups')
                .then(response => this.group = response.data.data)
            axios.post('api/v2/kassaSettingsGetOperationData/' , {id : this.operationField.id} )
                .then(response => this.dataObject = response.data.data)
        },


        editOperation() {

            if (this.dataObject.branch) {

                axios.post('api/v2/kassaSettingsEditOperationKassa', {
                    field_id: this.operationField.id,
                    field_value: this.dataObject.branch.id,
                    field_name: 'branch_id'
                })

            }

            if (this.dataObject.groupid) {

                axios.post('api/v2/kassaSettingsEditOperationKassa', {
                    field_id: this.operationField.id,
                    field_value: this.dataObject.groupid.id,
                    field_name: 'group_id'
                })


            }
            //--------------------------------- Send Checkbox -------------------------------

            if (this.dataObject.cash) {

                axios.post('api/v2/kassaSettingsEditOperationCheckbox', {
                    field_id: this.operationField.id,
                    field_name: "cash",
                    field_value: this.dataObject.cash,
                })

            }else{
            axios.post('api/v2/kassaSettingsEditOperationCheckbox', {
                field_id: this.operationField.id,
                field_name: "cash",
                field_value: 0,
            })
        }

           if (this.dataObject.beznal) {

                axios.post('api/v2/kassaSettingsEditOperationCheckbox', {
                    field_id: this.operationField.id,
                    field_name: "beznal",
                    field_value: this.dataObject.beznal,
                })

            }else{
                axios.post('api/v2/kassaSettingsEditOperationCheckbox', {
                    field_id: this.operationField.id,
                    field_name: "beznal",
                    field_value: 0,
                })
            }

           if (this.dataObject.comin) {

                axios.post('api/v2/kassaSettingsEditOperationCheckbox', {
                    field_id: this.operationField.id,
                    field_name: "coming",
                    field_value: this.dataObject.comin,
                })

            }else{
                axios.post('api/v2/kassaSettingsEditOperationCheckbox', {
                    field_id: this.operationField.id,
                    field_name: "coming",
                    field_value: 0,
                })
            }

            if (this.dataObject.out) {

                axios.post('api/v2/kassaSettingsEditOperationCheckbox', {
                    field_id: this.operationField.id,
                    field_name: "out",
                    field_value: this.dataObject.out,
                })

            }else{
                axios.post('api/v2/kassaSettingsEditOperationCheckbox', {
                    field_id: this.operationField.id,
                    field_name: "out",
                    field_value: 0,
                })
            }

            Vue.$toast.open({message: 'Данные обновлены', type: 'success', duration: 5000, position: 'top-right'});

            this.$emit('get-method')

            this.branch = ''
            this.group = ''
            this.groupid = ''
            this.groupid = ''

        },

        editField(e, name, type) {

            if (type) {
                axios.post('api/v2/kassaSettingsEditOperationName', {
                    field_id: this.operationField.id,
                    field_name: name,
                    field_value: e
                })
            } else {
                const value = e.target.value;
                const key = e.currentTarget.getAttribute('name');
                axios.post('api/v2/kassaSettingsEditOperationName', {
                    field_id: this.operationField.id,
                    field_name: key,
                    field_value: value
                })
            }
        },


        //--------------------------------- Удаляем операцию -----------------------------//

        deleteField(){
            axios.delete('api/v2/kassaOperations/' + this.operationField.id)
            Vue.$toast.open({message: 'Операция удалена' ,type: 'success',duration: 5000,position: 'top-right'});
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

