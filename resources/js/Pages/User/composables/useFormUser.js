import { reactive, computed, getCurrentInstance, ref } from "vue";
import { required, helpers, sameAs, email, minLength } from "@vuelidate/validators";
import useHelper from "@/composables/useHelper";
import useVuelidate from "@vuelidate/core";
import { useStore } from "vuex";
const isDiferentZero =  (param,param2) => (value) => {
    if(param.value ===1 || param.value ===2){
        return true
    }
    return value != 0 && value !=null;
};
const useFormuser = (formProps) => {
    const form = reactive(formProps);
    const store = useStore()
    const companies = ref([])
    const typeRol = ref(0)
    const { emit } = getCurrentInstance();
    const { makeToast } = useHelper();
    const rules = computed(() => ({
        username: { required },
        name: { required },
        password: { required,minLength:minLength(8) },
        email: { required, email },
        confirm_password: { sameAsPassword: sameAs(form.password) },
        company_id: {
            required,
            isDiferentZero: helpers.withMessage(
                "You must select an option",
                isDiferentZero(typeRol)
            ),
        },
        rols: {
            required,
        },
    }))
    const onChangeRol =(type)=>{
        typeRol.value = type
        getCompanies(type,true)
    }

    const getCompanies = async(type =1,change=false) =>{
        if(form.company_id !=0 || form.company_id !=null){
            if(form.rols.length >0 && change ==false){
                const res = await axios.get(`/company/getCompaniesDropdown/${form.rols[0]}`)
                companies.value = res.data
            }else{
                const res = await axios.get(`/company/getCompaniesDropdown/${type}`)
                form.company_id =0
                companies.value = res.data
            }
        }else{
            const res = await axios.get(`/company/getCompaniesDropdown/${type}`)
            form.company_id =0
            companies.value = res.data
        }

    }
    const v$ = useVuelidate(rules, form);
    const submitForm = async () => {
        const isFormCorrect = await v$.value.$validate();
        if (!isFormCorrect) return
        let res
        if (form.id == 0)
            res = await axios.post('/user', form)
        else
            res = await axios.put(`/user/${form.id}`, form)
        const { ok, message, value } = res.data
        if (ok) {
            if (form.id == 0) {
                store.commit('users/addDataTable', value)
                store.commit("users/setFormUser", 0);
            }
            else
                store.commit('users/updateItem', value)
            makeToast(message)
            emit('closeModal')
            emit('generateTable')
        } else {
            makeToast(message, 'error')
        }
    }

    getCompanies()

    return {
        form,
        submitForm,
        v$,
        onChangeRol,
        companies,
        typeRol
    }
}

export default useFormuser