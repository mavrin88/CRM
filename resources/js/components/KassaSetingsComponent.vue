<template>
    <div>

                                        <!-- Виды операций -->
        <edit-operations-in-kassa_settings :can="can" @get-method="updateDataWhenExitModalEditOptions" ref="getmodaleditoperations" v-if="can.kassa_add_operation_type"></edit-operations-in-kassa_settings>

                                    <!-- Группы -->
        <edit-groups-in-kassa_settings :can="can" @get-method="updateDataWhenExitModalEditGroups" ref="getmodaleditgroups" v-if="can.kassa_add_operation_type"></edit-groups-in-kassa_settings>



        <b-modal id="coming" title="Новая операция" @ok="handleSubmit" @hidden="resetModal" centered ok-only ok-title="Добавить">
            <div class="card-body py-0">
                <form ref="formSettingsGroup" @submit.stop.prevent="handleSubmit">

                    <div class="radio-group-toggle mb-5 text-center">
                        <input class="mx-3" type="checkbox" id="comin" value="1" v-model="checkbox.coming">
                        <label for="comin">Приход</label>
                        <input class="mx-3" type="checkbox" id="out" value="1" v-model="checkbox.out">
                        <label for="out">Расход</label>
                        <input class="mx-3" type="checkbox" id="cash" value="1" v-model="checkbox.cash">
                        <label for="cash">Наличные</label>
                        <br>
                        <input class="mx-3" type="checkbox" id="beznal" value="1" v-model="checkbox.beznal">
                        <label for="beznal">Экваринг</label>
                        <br>

                    </div>

                    <div class="form-group row">
                        <label class="col-sm-3 col-form-label">Наименование</label>
                        <div class="col-sm-9">
                            <input class="form-control" v-model="name">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-3 col-form-label">Касса</label>
                        <div class="col-sm-9">
                            <dynamic-select
                                :options="branches"
                                v-model="branch"
                                option-value="id"
                                option-text="name"
                                @input="getGroupsInKassa()"
                                placeholder="Введите для поиска кассы"/>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-3 col-form-label">Группа</label>
                        <div class="col-sm-9">
                            <dynamic-select
                                :options="kassaGroups"
                                v-model="group"
                                option-value="id"
                                option-text="name"
                                placeholder="Введите для поиска группы"/>
                        </div>
                    </div>
                    <div class="form-group row">
                        <textarea v-model="coment" class="form-control" rows="3" placeholder="Коментарий"></textarea>
                    </div>
                </form>
            </div>
        </b-modal>



        <b-modal id="group" title="Новая группа" @ok="saveGroup" @hidden="resetModalGroup" centered ok-only ok-title="Добавить">
            <div class="card-body py-0">
                <form>
                    <div class="form-group row">
                        <label class="col-sm-3 col-form-label">Наименование</label>
                        <div class="col-sm-9">
                            <input class="form-control" v-model="group_name">
                        </div>
                    </div>
                    <div class="form-group row">
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
                </form>
            </div>
        </b-modal>

        <div class="card">
            <b-tabs content-class="mt-3" justified>
                <b-tab title="Виды операций" @click="getOperationTypes" active>
                    <!-- Панель над фильтром -->
                    <div class="row mt-3">
                        <div class="col-lg-12">
                            <div class="card mb-2">
                                <div class="card-header">
                                    <div class="row align-items-center">
                                        <div class="col">
                                            <button
                                                v-if="can.kassa_add_operation_type"
                                                v-b-modal="'coming'"
                                                @click="getBranches"
                                                class="btn btn-sm btn-success">Добавить операцию</button>
                                        </div>
                                        <div class="col-auto">
                                            <button class="btn btn-sm btn-info">Фильтр</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Список клиентов -->
                    <div class="card-body pb-0">
                        <b-table

                            hover
                            sticky-header="700px"
                            :items="operation_types"
                            :fields="fields"
                            head-variant="light"
                            @row-clicked= "rowSelectedEditOperations">

                            <template v-slot:cell(coming)="row">
                                <span v-if="row.item.coming" class="text-success">●</span>
                            </template>
                            <template v-slot:cell(out)="row">
                                <span v-if="row.item.out" class="text-success">●</span>
                            </template>
                            <template v-slot:cell(cash)="row">
                                <span v-if="row.item.cash" class="text-success">●</span>
                            </template>
                            <template v-slot:cell(beznal)="row">
                                <span v-if="row.item.beznal" class="text-success">●</span>
                            </template>


                        </b-table>
                    </div>
                </b-tab>
                <b-tab @click="getAllGroups" title="Группы">
                    <!-- Панель над фильтром -->
                    <div class="row mt-3">
                        <div class="col-lg-12">
                            <div class="card mb-2">
                                <div class="card-header">
                                    <div class="row align-items-center">
                                        <div class="col">
                                            <button v-b-modal="'group'" @click="getBranches" class="btn btn-sm btn-success">Добавить группу</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-body pb-0">
                        <b-table

                            hover
                            sticky-header="700px"
                            :items="groups"
                            :fields="groupsFields"
                            head-variant="light"
                            @row-clicked= "rowSelectedEditGroups">

                        </b-table>
                    </div>
                </b-tab>
                <b-tab disabled title="Кассы"></b-tab>
                <b-tab disabled title="Прочие настройки"></b-tab>

            </b-tabs>
        </div>

    </div>
</template>

<script>
    import Vue from "vue";

    export default {
        data() {
            return{
                can: [],
                name: '',
                group_name: '',
                coment: '',
                branch: '',
                group: '',
                branches: [],
                groups: [],
                kassaGroups: [],
                operation_types: [],
                checkbox: {
                    comin: '',
                    out: '',
                    cash: '',
                    beznal: '',
                },

                fields: [
                    {
                        key: 'name',
                        label: 'Название',
                    },
                    {
                        key: 'coming',
                        label: 'Приход',
                    },
                    {
                        key: 'out',
                        label: 'Расход',
                    },
                    {
                        key: 'cash',
                        label: 'Наличные',
                    },
                    {
                        key: 'beznal',
                        label: 'Экваринг',
                    },
                    {
                        key: 'coment',
                        label: 'Коментарий',
                    },
                    {
                        key: 'branch',
                        label: 'Касса',
                    },
                    {
                        key: 'group',
                        label: 'Группа',
                    }
                ],
                groupsFields: [
                    {
                        key: 'name',
                        label: 'Название',
                    },
                    {
                        key: 'branch',
                        label: 'Касса',
                    }
                ],

            }
        },

        created(){
            this.getOperationTypes()
        },

        methods: {

            resetModalGroup(){
                this.group_name = ''
                this.branch = ''
            },

            handleSubmit(bvModalEvt){

                if (!this.branch || !this.checkbox || !this.name) {
                    bvModalEvt.preventDefault()
                    Vue.$toast.open({message: 'Заполните все необходимые поля' ,type: 'error',duration: 5000,position: 'top-right'});
                } else {
                    axios.post('api/v2/operation_type', {
                        name: this.name,
                        coming: this.checkbox.coming,
                        out: this.checkbox.out,
                        cash: this.checkbox.cash,
                        beznal: this.checkbox.beznal,
                        branch_id: this.branch.id,
                        group_id: this.group.id,
                        coment: this.coment,
                    })

                    Vue.$toast.open({message: 'Вид операции добавлен' ,type: 'success',duration: 5000,position: 'top-right'});
                    this.getOperationTypes()
                }

            },

            saveGroup(bvModalEvt){

                if (!this.branch || !this.group_name) {
                    bvModalEvt.preventDefault()
                    Vue.$toast.open({message: 'Заполните все необходимые поля' ,type: 'error',duration: 5000,position: 'top-right'});
                } else {
                    axios.post('api/v2/kassaGroups', {
                        name: this.group_name,
                        branch_id: this.branch.id,
                    })

                    Vue.$toast.open({message: 'Группа добавлена' ,type: 'success',duration: 5000,position: 'top-right'});
                    this.getAllGroups()
                }

            },

            getOperationTypes(){
                axios.get('api/v2/operation_type')
                    .then(response => {this.operation_types = response.data.data, this.can = response.data.can})
            },

            getBranches(){
                axios.get('api/v2/getbranches')
                    .then(response => this.branches = response.data.data)
            },

            getGroupsInKassa(){
                axios.post('api/v2/kassas', {branch_id: this.branch.id})
                    .then(response => this.kassaGroups = response.data.data)
            },

            getAllGroups(){
                axios.get('api/v2/kassaGroups')
                    .then(response => this.groups = response.data.data)
            },


            //----------------------- Show Edit Groups Modal ----------------------------//


            rowSelectedEditGroups(row) {

                    this.$refs.getmodaleditgroups.showModalEditGroupsKassaSettings(row)
            },


            //----------------------- Show Edit Operations Modal ----------------------------//

            rowSelectedEditOperations(row) {

                    this.$refs.getmodaleditoperations.showModalEditOperationKassaSettings(row)
            },

            //------------------------- Update and reset Data ----------------------------//

            updateDataWhenExitModalEditGroups(){

                this.getAllGroups()
            },

            updateDataWhenExitModalEditOptions(){

                this.getOperationTypes()
            },

            resetModal(){
                this.name = ''
                this.checkbox = ''
                this.branch = ''
                this.group_name = ''
                // this.coment = ''

            },


        }
    }
</script>
