require('./bootstrap');

import { App, plugin } from '@inertiajs/inertia-vue'
import Vue from 'vue';
import Buefy from 'buefy'
import VueTimeago from 'vue-timeago'
import { ToastProgrammatic as Toast } from 'buefy'
import { InertiaProgress } from '@inertiajs/progress'

Vue.use(plugin)
Vue.use(Buefy);
Vue.use(VueTimeago, {
  name: 'timeago',
  locale: 'en',
  locales: {
    'en': require('date-fns/locale/en'),
  },
  converter(date, locale, { includeSeconds, addSuffix = true }) {
    const distanceInWordsStrict = require('date-fns/distance_in_words_strict')
    return distanceInWordsStrict(Date.now(), date, { locale, addSuffix, includeSeconds });
  }
})

Vue.mixin({
  methods: {
    route,
    successToast: message => Toast.open({
      duration: 2500,
      message: message,
      position: 'is-bottom',
      type: 'is-success'
    }),
    errorToast: message => Toast.open({
      duration: 2500,
      message: message,
      position: 'is-bottom',
      type: 'is-danger'
    })
  }
});

Vue.component('app-layout', require('@/Layouts/AppLayout').default);
Vue.component('course-navigator', require('./components/CourseNavigator.vue').default);
Vue.component('video-player', require('./components/VideoPlayer.vue').default);
Vue.component('create-reply', require('./components/CreateReply.vue').default);
Vue.component('reply-card', require('./components/ReplyCard.vue').default);

InertiaProgress.init({
  delay: 0,
  color: '#33C2CF',
  includeCSS: true,
});

const el = document.getElementById('app')

if(el) {
  new Vue({
    render: h => h(App, {
      props: {
        initialPage: el.dataset.page ? JSON.parse(el.dataset.page) : {},
        // resolveComponent: name => require(`./Pages/${name}`).default,
        resolveComponent: name => import(`./Pages/${name}`).then(module => module.default),

      },
    }),
  }).$mount(el)
}
else {
  const app = new Vue({
      el: '#static',
  });
}



// Bulma NavBar Burger Script
document.addEventListener('DOMContentLoaded', function () {
    const $navbarBurgers = Array.prototype.slice.call(document.querySelectorAll('.navbar-burger'), 0);
    if ($navbarBurgers.length > 0) {
        $navbarBurgers.forEach(function ($el) {
            $el.addEventListener('click', function () {
                let target = $el.dataset.target;
                let $target = document.getElementById(target);
                $el.classList.toggle('is-active');
                $target.classList.toggle('is-active');
            });
        });
    }
});
