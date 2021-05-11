<template>

  <b-modal id="filter" size="lg" title="Фильтр по клиентам" @ok="getFilter" centered ok-only ok-title="Фильтровать">
    <div class="card-body py-0">

<!--      <b-form-group>-->
<!--        <b-form-radio-group-->
<!--            id="radio-group-1"-->
<!--            v-model="selected"-->
<!--            :options="options"-->
<!--            name="radio-options"-->
<!--        ></b-form-radio-group>-->
<!--      </b-form-group>-->

      <div class="form-row align-items-center">
      <div class="form-group row col-6">
        <div class="col-10">
          <b-form-checkbox
              v-model="selected"
              value="active"
              @change="clearDate">
            Действующий контракт
          </b-form-checkbox>
        </div>
      </div>
      <div class="form-group row col-6">
        <div class="col-10">
          <b-form-checkbox v-model="selected" value="notActive">Контракт закончился</b-form-checkbox>
        </div>
      </div>
      </div>

      <div class="form-row align-items-center">
        <div class="form-group row col-4">
          <label class="col-auto">Контракт от:</label>
          <div class="col-10">
            <date-picker
                v-model="contractFrom"
                :lang="lang"
                :disabled="selected"
                :editable="false"
                value-type="YYYY-MM-DD"
                format="DD.MM.YYYY">
            </date-picker>
          </div>
        </div>
        <div class="form-group row col-4">
          <label class="col-auto">Контракт до:</label>
          <div class="col-10">
            <date-picker
                v-model="contractTo"
                :lang="lang"
                :disabled="selected"
                :editable="false"
                value-type="YYYY-MM-DD"
                format="DD.MM.YYYY">
            </date-picker>
          </div>
        </div>
        <div class="form-group row col-3">
          <div class="col-10">
            <b-form-checkbox v-model="type" value="main">Основной</b-form-checkbox>
          </div>
        </div>
        <div class="form-group row col-1">
          <div class="col-10">
            <b-form-checkbox v-model="type" value="vm">ВМ</b-form-checkbox>
          </div>
        </div>
      </div>

<!--      <div class="form-row align-items-center">-->
<!--        <div class="form-group row col-6">-->
<!--          <label class="col-auto">Окончание контракта &lt; :</label>-->
<!--          <div class="col-10">-->
<!--            <input type="email" class="form-control">-->
<!--          </div>-->
<!--        </div>-->
<!--        <div class="form-group row col-3">-->
<!--          <label class="col-auto">ПК до :</label>-->
<!--          <div class="col-10">-->
<!--            <input type="email" class="form-control">-->
<!--          </div>-->
<!--        </div>-->
<!--        <div class="form-group row col-3">-->
<!--          <label class="col-auto">ВМ до :</label>-->
<!--          <div class="col-10">-->
<!--            <input type="email" class="form-control">-->
<!--          </div>-->
<!--        </div>-->
<!--      </div>-->

<!--      <b-form-group label="Текущие">-->
<!--        <b-form-checkbox-group>-->
<!--          <b-form-checkbox value="orange">ПК</b-form-checkbox>-->
<!--          <b-form-checkbox value="apple">ВМ</b-form-checkbox>-->
<!--          <b-form-checkbox value="apple">ВЗ</b-form-checkbox>-->
<!--          <b-form-checkbox value="apple">НЗ</b-form-checkbox>-->
<!--          <b-form-checkbox value="apple">ЗЛ</b-form-checkbox>-->
<!--          <b-form-checkbox value="apple">ЛП</b-form-checkbox>-->
<!--          <b-form-checkbox value="apple">ШФ</b-form-checkbox>-->
<!--        </b-form-checkbox-group>-->
<!--      </b-form-group>-->


      <b-form-group label="Программа" v-slot="{ ariaDescribedby }">
        <b-form-checkbox-group
            id="checkbox-group-1"
            v-model="programm"
            :options="options"
            :aria-describedby="ariaDescribedby"
            name="flavour-1"
        ></b-form-checkbox-group>
      </b-form-group>


      <div class="form-row align-items-center">
<!--        <div class="form-group row col-3">-->
<!--          <label class="col-auto">Д.Р до :</label>-->
<!--          <div class="col-10">-->
<!--            <input class="form-control">-->
<!--          </div>-->
<!--        </div>-->
        <div class="form-group row col-4">
          <label class="col-auto">Возраст от :</label>
          <div class="col-10">
            <input class="form-control" v-model="birthdayFrom">
          </div>
        </div>
        <div class="form-group row col-4">
          <label class="col-auto">Возраст до :</label>
          <div class="col-10">
            <input class="form-control" v-model="birthdayTo">
          </div>
        </div>
        <div class="form-group row col-4">
          <label class="col-auto">Год рождения :</label>
          <div class="col-10">
            <date-picker
                v-model="yearBirthday"
                type="year"
                @change="clearBirthday"
                value-type="YYYY">
            </date-picker>
          </div>
        </div>
      </div>

    </div>
  </b-modal>

</template>


<script>

import DatePicker from 'vue2-datepicker';
Vue.use(DatePicker);

export default {
  components: { DatePicker },
  data() {
    return {

      programm: [],
      allSelected: false,
      indeterminate: false,

      options: [
        { text: 'ПК', value: 'Перший крок' },
        { text: 'ВМ', value: 'Відкрий можливості' },
        { text: 'ВЗ', value: 'Вперед до Зірок' },
        { text: 'НЗ', value: 'Народження зірки' },
        { text: 'ЗЛ', value: 'Зірка лева' },
        { text: 'ЛП', value: 'Лідер прайду' },
        { text: 'ШФ', value: 'Школа футболу' },
      ],

      type: false,
      dataVm: '',
      contractFrom: '',
      contractTo: '',
      birthdayFrom: '',
      birthdayTo: '',
      name: '',
      surname: '',
      phone: '',
      email: '',
      users: [],
      yearBirthday: '',
      lang: {
        formatLocale: {
          firstDayOfWeek: 1,
          weekdaysMin: ['Вс', 'Пн', 'Вт', 'Ср', 'Чт', 'Пт', 'Сб'],
          monthsShort: ['Янв', 'Февраль', 'Март', 'Апрель', 'Май', 'Июнь', 'Июль', 'Август', 'Сентябрь', 'Октябрь', 'Ноябрь', 'Декабрь'],
        },
      },
      selected: false
    }
  },

  methods: {

    clearDate() {
      this.contractFrom = ''
      this.contractTo = ''
    },

    clearBirthday() {
      this.birthdayFrom = ''
      this.birthdayTo = ''
    },

    showUserFilter(){
      this.$bvModal.show('filter')
    },

    async getFilter() {

      try {
        // let loader = this.$loading.show({
        //   container: this.fullPage ? null : this.$refs.formContainer,
        //   canCancel: true,
        // });

        let res = await axios.post('api/v2/baseFilter', {
          activeContract: this.selected,
          typeContract: this.type,
          contractDate: [this.contractFrom, this.contractTo],
          birthdayDate: [this.birthdayFrom, this.birthdayTo],
          yearBirthday: this.yearBirthday,
          programm: this.programm
        })

        if(res.status == 200){

          this.$emit('get-method', {users: res.data.data})

          // loader.hide()
        }
        return res.data.data
      }
      catch (err) {
        console.error(err);
      }
    },

    // getFilter(){
    //   axios.post('api/v2/getTest', {
    //     activeContract: this.selected,
    //     typeContract: this.type,
    //     contractDate: [this.contractFrom, this.contractTo],
    //     birthdayDate: [this.birthdayFrom, this.birthdayTo],
    //     yearBirthday: this.yearBirthday
    //   })
    //       .then(response => this.users = response.data)
    // },

  }
}
</script>

