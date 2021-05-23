/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */


import Vue from 'vue'
import VueRouter from 'vue-router'

Vue.use(VueRouter)

import Agregator from './components/AgregatorComponent.vue'
import Journal from './components/JournalComponent.vue'
import Users from './components/Users/UsersComponent'
import Permissions from './components/PermissionsComponent'
import UserAccess from './components/Access/UserAccessComponent'
import Roles from './components/Role/RolesComponent'
import Region from './components/RegionComponent'
import Kassa from './components/KassaComponent'
import KassaSettings from './components/KassaSetingsComponent'
import Base from './components/BaseComponent'
import Promoter from './components/PromoterComponent'
import Source from './components/GuideComponent'
import Warehousegroups from './components/Warehouse/WarehouseGroupsComponent'
import Warehouse from './components/Warehouse/WarehouseComponent'
import Settings from './components/SettingsComponent'
import Testing from './components/TestingComponent'


const routes = [
    { path: '/', component: Region },
    { path: '/journal', component: Journal },
    { path: '/agregator', component: Agregator },
    { path: '/users', component: Users },
    { path: '/permissions', component: Permissions },
    { path: '/useraccess', component: UserAccess },
    { path: '/roles', component: Roles },
    { path: '/kassa', component: Kassa },
    { path: '/kassa_setings', component: KassaSettings },
    { path: '/base', component: Base },
    { path: '/promouter', component: Promoter },
    { path: '/source', component: Source },
    { path: '/warehouse', component: Warehouse },
    { path: '/testing', component: Testing },
    { path: '/warehouse_groups', component: Warehousegroups },
    { path: '/settings', component: Settings },
]

const router = new VueRouter({
    mode: 'history',
    routes
})


require('./bootstrap');

window.Vue = require('vue');

import VTitle from 'v-title';
Vue.use(VTitle);

import VueTheMask from 'vue-the-mask'
Vue.use(VueTheMask)

import VueSimpleAlert from "vue-simple-alert";
Vue.use(VueSimpleAlert);

import { BootstrapVue, IconsPlugin, BootstrapVueIcons } from 'bootstrap-vue'
Vue.use(BootstrapVue)
Vue.use(BootstrapVueIcons)
// import 'bootstrap/dist/css/bootstrap.css'
import 'bootstrap-vue/dist/bootstrap-vue.css'


// import Vuetify from 'vuetify'
// import 'vuetify/dist/vuetify.min.css'

// Vue.use(Vuetify)

/**
 * The following block of code may be used to automatically register your
 * Vue components. It will recursively scan this directory for the Vue
 * components and automatically register them with their "basename".
 *
 * Eg. ./components/ExampleComponent.vue -> <example-component></example-component>
 */

// const files = require.context('./', true, /\.vue$/i)
// files.keys().map(key => Vue.component(key.split('/').pop().split('.')[0], files(key).default))

//Users
Vue.component('users-component', require('./components/Users/UsersComponent.vue').default);
Vue.component('addnewuser-component', require('./components/Users/AddNewUserComponent.vue').default);
Vue.component('showuser-component', require('./components/Users/ShowUserComponent.vue').default);
Vue.component('userfilter-component', require('./components/Users/UserFilterComponent.vue').default);

// Base
Vue.component('add-new-base-component', require('./components/AddNewBaseComponent.vue').default);

//Roles
Vue.component('roles-component', require('./components/Role/RolesComponent.vue').default);
Vue.component('addnewrole-component', require('./components/Role/AddNewRoleComponent.vue').default);
Vue.component('showrole-component', require('./components/Role/ShowRoleComponent.vue').default);

//UserAccess
Vue.component('user-access-component', require('./components/Access/UserAccessComponent.vue').default);
Vue.component('show-user-access-component', require('./components/Access/ShowUserAccessComponent.vue').default);


Vue.component('example-component', require('./components/ExampleComponent.vue').default);
Vue.component('base-component', require('./components/BaseComponent.vue').default);
Vue.component('base-filter-component', require('./components/BaseFilterComponent.vue').default);
Vue.component('base-modal-component', require('./components/BaseModal.vue').default);
Vue.component('dogovor-component', require('./components/DogovorComponent.vue').default);
Vue.component('show-dogovor-component', require('./components/ShowDogovorComponent.vue').default);
Vue.component('product-component', require('./components/ProductComponent.vue').default);
Vue.component('region-component', require('./components/RegionComponent.vue').default);
Vue.component('filter-component', require('./components/Filter/FilterComponent.vue').default);

Vue.component('calendar-component', require('./components/Calendar.vue').default);
Vue.component('input-form', require('./components/InputComponent.vue').default);
Vue.component('journal-component', require('./components/JournalComponent.vue').default);
Vue.component('permissions-component', require('./components/PermissionsComponent.vue').default);
Vue.component('upload-component', require('./components/UploadFile.vue').default);
Vue.component('promoter-component', require('./components/PromoterComponent.vue').default);
Vue.component('agregator-component', require('./components/AgregatorComponent.vue').default);


Vue.component('menu-component', require('./components/Menu/MenuComponent.vue').default);
Vue.component('user-block', require('./components/Menu/UserBlock.vue').default);
Vue.component('menu-list', require('./components/Menu/MenuList.vue').default);


Vue.component('testing-component', require('./components/TestingComponent.vue').default);

Vue.component('chatbox-component', require('./components/ChatboxComponent.vue').default);

Vue.component('add-warehouse-component', require('./components/Warehouse/AddWarehouseComponent.vue').default);
Vue.component('edit-warehouse-component', require('./components/Warehouse/EditWarehouseComponent.vue').default);
Vue.component('add-article-component', require('./components/Warehouse/AddArticleComponent.vue').default);
Vue.component('show-article-component', require('./components/Warehouse/ShowArticleComponent.vue').default);
Vue.component('show-log-component', require('./components/Warehouse/ShowLogComponent.vue').default);

//---------------------------- Kassa_Settings ----------------------------------//

Vue.component('kassa-component', require('./components/KassaComponent.vue').default);
Vue.component('kassa-setings-component', require('./components/KassaSetingsComponent.vue').default);

Vue.component('edit-operations-in-kassa_settings', require('./components/Kassa_Settings/EditOperationKassaSettings.vue').default);

Vue.component('edit-groups-in-kassa_settings', require('./components/Kassa_Settings/EditGroupsKassaSettings.vue').default);


/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */


// const app = new Vue({
//     el: '#app',
// });

const app = new Vue({
    el: '#app',
    router,
});
