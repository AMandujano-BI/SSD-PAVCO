import axios from "axios";


const defaultValue = {
    id: 0,
    name: '',
    lastname: "",
    username: "",
    email: "",
    password: "",
    confirm_password: "",
    country_id: 0,
}
const state = {
    form: defaultValue,
    tableUsers: []
}

const mutations = {
    setDataTable(state, data) {
        state.tableUsers = data
    },
}


const actions = {
    async getUsers({ commit }, type) {
        const res = await axios.get(`/user/getUsers/${type}`);
        const data = res.data
        console.log(data)
        commit('setDataTable', data)
    },
    async createUser({ commit }, form) {
        let res
        console.log(form)
        if (form.id == 0)
            res = await axios.post('/user', form)
        else
            res = await axios.put(`/user/${form.id}`, form)
        const data = res.data
        console.log(data)
        return data
        // commit('setDataTable', data)
    },
}


export default {
    namespaced: true,
    state,
    mutations,
    actions
}