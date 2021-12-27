import axios from "axios"
import useHelper from "@/composables/useHelper";


const defaultValue = {
    id: 0,
    name: "",
    address: "",
    city: "",
    type: 1,
    state: "",
    zip: "",
    phone: "",
    fax: "",
    customer: 0,
    distributor: 0,
    notes: "",
    country_id: 0,
    company_id: 0,
}
const state = {
    form: defaultValue,
    tableCompanies: [],
    tableDistributors:[]
}
const mutations = {
    setFormCompany(state, id) {
        if (id == 0) {
            state.form = defaultValue
        } else {
            const company = state.tableCompanies.find(item => item.id == id)
            state.form = { ...company }

        }
    },
    setDataTable(state, data) {
        state.tableCompanies = [... data]
    },
    setDistributors(state, data) {
        state.tableDistributors = [... data]
    },
    addDataTable(state, data) {
        state.tableCompanies = [data, ...state.tableCompanies]
    },
    deleteItem(state, id) {
        state.tableCompanies = state.tableCompanies.filter(item => item.id != id)
    },
    updateItem(state, data) {
        const index = state.tableCompanies.findIndex(item => item.id == data.id)
        state.tableCompanies[index] = data
    }
}
const actions = {
    async getCompanies({ commit }, type) {
        const res = await axios.get(`/company/getCompanies/${type}`);
        const data = res.data
        console.log(data)
        return data
    },
    async getDistributors({ commit },id) {
        console.log(id)
        const res = await axios.get(`/company/getDistributors/${id}`);
        const data = res.data
        return data
    },
    async deleteCompany({ commit }, id) {
        try {
            const res = await axios.delete(`/company/${id}`);
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