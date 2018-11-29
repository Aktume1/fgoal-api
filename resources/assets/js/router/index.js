import Vue from 'vue';
import Router from 'vue-router';

Vue.use(Router);

import ListObjective from '../components/Objective/ListObjective.vue';

export default new Router({
    routes: [
        { path: '/group/:groupId/objectives', name: 'ListObjective', component: ListObjective }
    ],
    mode: 'history',
});
