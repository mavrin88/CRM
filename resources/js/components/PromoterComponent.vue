<template>
    <div>
        <header>
            <a><img src="/images/images_for_promouter/avatar.png"></a>
            <a><img src="/images/images_for_promouter/red.png"></a>
            <a><img src="/images/images_for_promouter/calendar.png"></a>
            <a><img src="/images/images_for_promouter/map-button.png"></a>
            <div class="menu-icon"><img src='/images/images_for_promouter/menu.svg'></div>
        </header>
        <main class="container-sm">
            <div class="menu">
                <img class="cross-icon" src="/images/images_for_promouter/cross.svg">
                <div class="icons">
                    <a>menu item</a>
                    <a>menu item</a>
                    <a>menu item</a>
                </div>
            </div>
            <div class="row">
                <div class="inputs">
                    <form @submit.prevent="send">
                        <div class="form-group col-md-12">
                            <input type="text" class="form-control" placeholder="Имя ребенка" v-model="child_name">
                        </div>
                        <div>
                            <div class="form-group col-md-7">
                                <input type="datetime" class="form-control col" id="inputEmail4"
                                       placeholder="17.04.21 17.00">
                            </div>


                            <div class="form-group col-md-4">

                                <b-form-select v-model="selected"
                                               :options="options"
                                               class="city-select"
                                ></b-form-select>

                            </div>
                            <div class="col-md-4">

                                <b-form-group label="Выберите близкого">
                                    <b-form-radio-group
                                        v-model="selected_2"
                                        :options="options_2"
                                        name="radio-inline"
                                    ></b-form-radio-group>
                                </b-form-group>

                            </div>
                        </div>
                        <div class="form-group col-md-4">
                            <input type="text" class="form-control" placeholder="Имя родителя"
                                   v-model="parent_name">
                        </div>


                        <input type="tel" placeholder="+38 (___) ___-__-__" v-model="parent_phone"
                               v-mask="'+38 (###) ###-##-##'">


                        <select class="form-select city-select" aria-label="Default select example">
                            <option selected value="1">Сихив</option>
                            <option value="2">Two</option>
                            <option value="3">Three</option>
                        </select>
                        <button type="submit" class="button send-button btn">Отправить</button>
                    </form>
                </div>


                <div class="conversation-block">
                    <div class="message-block">
                        <div class="receiver-block">
                            <img class="person-avatar" src='/images/images_for_promouter/avatar.png'>
                            <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. </p>
                        </div>
                        <div class="sender-block">
                            <img class="person-avatar" src='/images/images_for_promouter/avatar.png'>
                            <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. </p>
                        </div>
                        <div class="receiver-block">
                            <img class="person-avatar" src='/images/images_for_promouter/avatar.png'>
                            <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. </p>
                        </div>
                        <div class="sender-block">
                            <img class="person-avatar" src='/images/images_for_promouter/avatar.png'>
                            <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. </p>
                        </div>
                    </div>
                    <button>Написать сообщение...</button>
                </div>
            </div>
        </main>
    </div>
</template>

<script>

import VueToast from 'vue-toast-notification';
import 'vue-toast-notification/dist/theme-sugar.css';

Vue.use(VueToast);

import Vuelidate from 'vuelidate'

Vue.use(Vuelidate)
import {required, minLength, between} from 'vuelidate/lib/validators'
import Vue from "vue";

export default {
    data() {
        return {
            selected: '',
            selected_2: 'м',
            options: [
                {value: '', text: 'Возраст ребенка'},
                {text: '3', value: '3'},
                {text: '4', value: '4'},
                {text: '5', value: '5'},
                {text: '6', value: '6'},
                {text: '7', value: '7'},
                {text: '8', value: '8'},
            ],

            options_2: [
                {text: 'Мать', value: 'м'},
                {text: 'Отец', value: 'о'},
                {text: 'Родственник', value: 'р'},
            ],

            child_name: '',
            parent_name: '',
            parent_phone: '',
            branch: '',
            date: '',
            age: '',
            notes: '',

        }
    },

    validations: {
        name: {
            required,
            minLength: minLength(4)
        },
        age: {
            between: between(20, 30)
        }
    },

    mounted() {
    },

    methods: {

        setName(value) {
            this.name = value
            this.$v.name.$touch()
        },
        setAge(value) {
            this.age = value
            this.$v.age.$touch()
        },

        send() {
            Vue.$toast.open({
                message: 'Днные о ребенке успешно добавлены',
                type: 'success',
                duration: 5000,
                position: 'top-right'
            });

            if (this.selected_2 == "м") {

                axios.post('/api/v2/addClientFromPromoter/', {
                    child_name: this.child_name,
                    mother_phone: this.parent_phone,
                    mother_name: this.parent_name,
                    age: this.selected


                }).finally(() => (this.reset()))
            } else if (this.selected_2 == "о") {

                axios.post('/api/v2/addClientFromPromoter/', {
                    child_name: this.child_name,
                    father_phone: this.parent_phone,
                    father_name: this.parent_name,
                    age: this.selected


                }).finally(() => (this.reset()))
            } else {

                axios.post('/api/v2/addClientFromPromoter/', {
                    child_name: this.child_name,
                    other_relative_phone: this.parent_phone,
                    other_relative_name: this.parent_name,
                    age: this.selected


                }).finally(() => (this.reset()))
            }

        },

        reset() {
            this.mother_phone = '',
                this.child_name = '',
                this.mother_name = '',
                this.selected = ''


        },
    },
}
</script>


<style scoped>

html, body {
    height: 100%;
    margin: 0;
    padding: 0;
    overflow: hidden;
}

.main-logo {
    width: 500px;
    margin: 45px auto;
}

.inputs {
    display: flex;
    justify-content: space-between;
    flex-direction: column;
    width: 100%;
}

.inputs input {
    height: 95px;
    text-align: center;
    margin-bottom: 45px;
    border: 2px solid #044510;
    font-size: 28px;
    letter-spacing: 4px;
    border-radius: 5px;
}

.button {
    height: 95px;
    margin-bottom: 20px;
    border-radius: 5px;
    border: none;
    background: #044510;
    color: #f7f8f9;
    text-transform: uppercase;
    letter-spacing: 5px;
    font-size: 48px;
    font-weight: bold;
    line-height: normal;
}

.login-button {
    width: calc(100% - 30px);
    margin: 0 auto 20px;
}

.forgot-password-button {
    border: none;
    background: inherit;
    color: #044510;
    text-decoration: underline;
    letter-spacing: 2px;
    font-size: 28px;
}

header {
    margin: 45px;
    justify-content: space-between;
    display: flex;
    align-items: center;
}

header .menu-icon img {
    width: 78px;
}

.menu {
    position: absolute;
    background: #fff;
    height: 100%;
    top: 0;
    width: 100%;
    left: 0;
    display: none;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    overflow: hidden;
    z-index: 9;
}

.menu.open {
    display: flex;
}

.menu a {
    font-size: 48px;
    margin-bottom: 45px;
    color: #044510;
}

.menu .icons {
    display: flex;
    flex-direction: column;
}

.cross-icon {
    position: absolute;
    top: 45px;
    right: 45px;
    transform: rotate(45deg);
    width: 78px;
}

.inputs div {
    display: flex;
    justify-content: space-between;
}

.inputs div:not(.form-group) input:first-child {
    width: 75%;
}

.inputs div:not(.form-group) input:last-child {
    width: 20%;
}

.city-select {
    border: 2px solid #044510;
    margin-bottom: 45px;
    height: 95px;
    font-size: 28px;
    letter-spacing: 4px;
}

.city-select option {
    font-size: 12px;
}

.conversation-block {
    display: flex;
    flex-direction: column;
    border: 1px solid #044510;
    padding: 0;
    border-radius: 10px;
    width: calc(100% - (calc(var(--bs-gutter-x) / 2) + (var(--bs-gutter-x) / 2)));
    margin: 45px auto;
}

.conversation-block button {
    background: none;
    height: 80px;
    font-size: 44px;
    color: #8a8b90;
    border: none;
    border-top: 1px solid #044510;
    position: relative;
}

.conversation-block button:before {
    content: '';
    background-image: url(/images/images_for_promouter//arrow.png);
    width: 50px;
    height: 50px;
    display: block;
    background-repeat: no-repeat;
    background-position: center;
    background-color: #044510;
    border-radius: 50%;
    background-size: 50%;
    position: absolute;
    top: 50%;
    right: 5%;
    transform: translate(0%, -50%);
}

.message-block {
    padding: 25px;
    max-height: 21vh;
    overflow: scroll;
}

.sender-block {
    display: flex;
    align-items: flex-end;
    margin-bottom: 45px;
}

.receiver-block {
    display: flex;
    align-items: flex-end;
    margin-bottom: 45px;
    flex-direction: row-reverse;
}

.sender-block img {
    width: 80px;
    margin-right: 25px;
}

.receiver-block img {
    width: 80px;
    margin-left: 25px;
}

.sender-block p, .receiver-block p {
    margin: 0;
    border: 1px solid #044510;
    font-size: 22px;
    padding: 15px;
}

.send-button {
    margin-bottom: 0;
}

.map-image {
    width: 100%;
}

.shedule-header {
    display: flex;
    justify-content: flex-start;
    align-items: center;
    margin-bottom: 45px;
}

.shedule-header img {
    width: 60px;
    margin-right: 25px;
}

.shedule-header p {
    margin: 0;
    font-size: 60px;
    text-decoration: underline;
    letter-spacing: 5px;
}

.shedule-block {
    padding: 0 25px;
}

.shedule-block p {
    font-size: 36px;
    letter-spacing: 2px;
}

@media (min-width: 992px) {
    html, body {
        overflow: visible;
        max-width: 540px;
        margin: 0 auto;
    }
}

@media (min-width: 576px) {
    .modal-dialog-centered {
        max-width: 90%;
    }
}


</style>
