import axios from "axios";


const defaultValue = {
    id: 0,
    name: '',
    lastname: '',
    username: "",
    email: "",
    password: "",
    confirm_password: "",
    company_id: 0,
    rols: [],
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
            const user= state.tableUsers.find(item => item.id == id)
            const rolsArray = []
            user.rols.map(item =>{
                // rolsArray.push({value:item.id,label:item.name})
                rolsArray.push(item.id)
            })
            // user.rols  = rolsArray
            user.password ='123'
            user.confirm_password='123'
            state.form = { ...user }
            state.form.rols  = rolsArray

        }
    },
    addDataTable(state, data) {
        state.tableUsers= [data, ...state.tableUsers]
    },
    setDataTable(state, data) {
        state.tableUsers = data
    },
    deleteItem(state, id) {
        state.tableUsers = state.tableUsers.filter(item => item.id != id)
    },
    updateItem(state, data) {
        const index = state.tableUsers.findIndex(item => item.id == data.id)
        state.tableUsers[index] = data
    }
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