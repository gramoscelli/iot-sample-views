import Vue from 'vue';
import Vuex from 'vuex';
Vue.use(Vuex);


export default new Vuex.Store({
    state: {
        userid: 0
    },
    mutations: {
        change (state, id) {
            alert('change '+id);
            state.userid = id;
        }
    }
});
