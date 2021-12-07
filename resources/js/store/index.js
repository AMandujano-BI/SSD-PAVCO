import {createStore} from 'vuex'


export default createStore({
    state:{
        /* fullScreen - fullscreen form layout (e.g. login page) */
        isFullScreen: false,

        /* Aside */
        isAsideMobileExpanded: false,
        isAsideLgActive: false,
    },
    mutations:{
        basic (state, payload) {
            state[payload.key] = payload.value
          },
      
    },
    actions:{
        asideMobileToggle ({ commit, state }, payload = null) {
            const isShow = payload !== null ? payload : !state.isAsideMobileExpanded
      
            document.getElementById('app').classList[isShow ? 'add' : 'remove']('ml-60', 'lg:ml-0')
      
            document.documentElement.classList[isShow ? 'add' : 'remove']('m-clipped')
      
            commit('basic', {
              key: 'isAsideMobileExpanded',
              value: isShow
            })
          },
      
          asideLgToggle ({ commit, state }, payload = null) {
            commit('basic', { key: 'isAsideLgActive', value: payload !== null ? payload : !state.isAsideLgActive })
          },
      
          fullScreenToggle ({ commit, state }, value) {
            commit('basic', { key: 'isFullScreen', value })
      
            document.documentElement.classList[value ? 'add' : 'remove']('full-screen')
          },
    }

})
