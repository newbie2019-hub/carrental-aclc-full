import Vue from 'vue'
import App from './App.vue'
import vuetify from './plugins/vuetify'
import router from './router'
import './assets/css/styles.css'
import store from './store'
import Toast from './assets/js/toast'
import 'viewerjs/dist/viewer.css';
import VueViewer from 'v-viewer';

Vue.use(VueViewer);
Vue.mixin(Toast)
Vue.config.productionTip = false

new Vue({
  vuetify,
  router,
  store,
  render: h => h(App)
}).$mount('#app')
