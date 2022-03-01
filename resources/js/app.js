/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

window.Vue = require('vue');
window.$ = window.jQuery = require('jquery');

import { ServerTable,ClientTable, Event} from 'vue-tables-2';
import { extend, ValidationProvider, ValidationObserver } from 'vee-validate';
import { required, numeric} from "vee-validate/dist/rules";


// VALIDACIONES
extend("required",{
    ...required,
    message: "El CAMPO ES OBLIGATORIO"
});

extend("numeric",{
    ...numeric,
    message: "EL CAMPO SOLO ACEPTA NUMEROS"
});
extend("decimal", {
    validate: (value, { decimals = '*', separator = '.' } = {}) => {
      if (value === null || value === undefined || value === '') {
        return {
          valid: false
        };
      }
      if (Number(decimals) === 0) {
        return {
          valid: /^-?\d*$/.test(value),
        };
      }
      const regexPart = decimals === '*' ? '+' : `{1,${decimals}}`;
      const regex = new RegExp(`^[-+]?\\d*(\\${separator}\\d${regexPart})?([eE]{1}[-]?\\d+)?$`);

      return {
        valid: regex.test(value),
      };
    },
    message: 'El {_field_} campos debe contener solo valores decimales'
  })
/**
 * The following block of code may be used to automatically register your
 * Vue components. It will recursively scan this directory for the Vue
 * components and automatically register them with their "basename".
 *
 * Eg. ./components/ExampleComponent.vue -> <example-component></example-component>
 */

// const files = require.context('./', true, /\.vue$/i)
// files.keys().map(key => Vue.component(key.split('/').pop().split('.')[0], files(key).default))
Vue.use(ClientTable,{ } ,  false ,  'bootstrap4');
Vue.use(ServerTable,{ } ,  false ,  'bootstrap4');

Vue.component( 'ValidationProvider', ValidationProvider );
Vue.component( 'ValidationObserver', ValidationObserver );

Vue.component('create-products-component', require('./components/CreateProductsComponent.vue').default);
Vue.component('list-products-component', require('./components/ListProductsComponent.vue').default);

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

const app = new Vue({
    el: '#app',
});




/* ********** ********** ********** */
/*      SIDEBAR | MENÚ LATERAL      */
/* ********** ********** ********** */

// AL PRECIONAR EL BOTÓN HAMBURGUESA
$('#sne-toggle-sidebar').click(function () {
    showHideSidebar();
});

// AL PRECIONAR EL TRASFONDO DEL MENÚ
$('#sne-back-sidebar').click( function () {
    if ( $('#sne-back-sidebar').attr('sne-sidebar-status') == 'show' ) {
        showHideSidebar();
    }
});

// VALIDACIÓN PARA ACTIVAR O DESACTIVAR EL MENÚ
function showHideSidebar(params) {
    if ($('#sne-back-sidebar').is(':visible')) {
        $('#sne-sidebar').toggleClass('sidebar-left');
        $('#sne-back-sidebar').toggleClass('sne-siderbar-back-show');

        $('.sne-button-menu').toggleClass('open');

        $('#sne-back-sidebar').attr('sne-sidebar-status', 'hide');

        setTimeout(function(){
            $('#sne-sidebar').toggleClass('sne-sidebar-hide');
            $('#sne-back-sidebar').toggleClass('sne-sidebarback-hide');
        }, 200);
    } else {
        $('#sne-sidebar').toggleClass('sne-sidebar-hide');
        $('#sne-back-sidebar').toggleClass('sne-sidebarback-hide');

        $('.sne-button-menu').toggleClass('open');

        $('#sne-back-sidebar').attr('sne-sidebar-status', 'show');

        setTimeout(function(){
            $('#sne-sidebar').toggleClass('sidebar-left');
            $('#sne-back-sidebar').toggleClass('sne-siderbar-back-show');
        }, 20);
    }
}

/*   LINKS | SECCIONES   */

// AGREGAR CLASE selected
$( '.sidebar > .sidebar-sticky > .nav > .nav-item' ).on( 'click', function(){
    $( '.nav-item' ).removeClass( 'selected' );
    $( this ).addClass( 'selected' );
})
