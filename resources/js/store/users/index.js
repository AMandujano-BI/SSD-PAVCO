import axios from "axios";


const defaultValue = {
    id: 0,
    name: '',
    lastname: "",
    username: "",
    email: "",
    password: "",
    confirm_password: "",
    company_id: 0,
    rol_id: 0,
}
const state = {
    form: defaultValue,
    tableUsers: []
}

const mutations = {
    setFormUser(state, id) {
        if (id == 0) {
            state.form = defaultValue
        } else {
            const users = state.tableUsers.find(item => item.id == id)
            state.form = { ...users }

        }
    },
    setDataTable(state, data) {
        state.tableUsers = data
    },
    deleteItem(state, id) {
        state.tableUsers = state.tableUsers.filter(item => item.id != id)
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
    async deleteUser({ commit }, id) {
        try {
            const res = await axios.delete(`/user/${id}`);
            //  commit('deleteItem', id)
            return res
        } catch (e) {
            return e
        }

    },
}


export default {
    namespaced: true,
    state,
    mutations,
    actions
}