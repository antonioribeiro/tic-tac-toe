window._ = require('lodash')

window.Popper = require('popper.js').default

/**
 * We'll load jQuery and the Bootstrap jQuery plugin which provides support
 * for JavaScript based Bootstrap features such as modals and tabs. This
 * code may be modified to fit the specific needs of your application.
 */

try {
    window.$ = window.jQuery = require('jquery')

    require('bootstrap')
} catch (e) {}

/**
 * We'll load the axios HTTP library which allows us to easily issue requests
 * to our Laravel back-end. This library automatically handles sending the
 * CSRF token as a header based on the value of the "XSRF" token cookie.
 */

window.axios = require('axios')

window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest'

/**
 * Vue
 */
window.Vue = require('vue')

/**
 * Vuex
 */
window.Vuex = require('vuex')

/**
 * Moment.js
 */
window.moment = require('moment')
moment.locale('pt-br')

/**
 * SweetAlert
 */
import VueSwal from 'vue-swal'
Vue.use(VueSwal)

/**
 * Vue Bootstrap
 */
import { Modal } from 'bootstrap-vue/es/components'
import { Button } from 'bootstrap-vue/es/components'
Vue.use(Modal)
Vue.use(Button)

/**
 * Autoload Vue components
 */
const files = require.context('./components/app/', true, /\.vue$/i)
files.keys().map(key => {
    const name = 'App' + _.last(key.split('/')).split('.')[0]
    return Vue.component(name, files(key))
})

require('app')
