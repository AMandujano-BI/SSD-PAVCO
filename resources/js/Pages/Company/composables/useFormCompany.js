

import { required, helpers,maxLength } from "@vuelidate/validators";
import { reactive, ref, getCurrentInstance } from "vue";
import useVuelidate from "@vuelidate/core";
import axios from "axios";
import useHelper from "@/composables/useHelper";
import { useStore } from "vuex";

const isDiferentZero = (value) => {
    return value != 0;
};

const validationCustomer = (param) => helpers.withParams(
    { type: 'validationCustomer', value: param }, (value) => {
        const type = param.split('/')[1]
        const number = param.split('/')[0]
        if (type.toString() =='normal') {
            if (parseInt(number) ==1) {
                if (value == undefined || value == null || value == 0) {
                    return { $valid: false }
                }
                return { $valid: true }
            } else {

                return { $valid: true }
            }
        } else {

            if (value ==1) {
                if (number == undefined || number == null || number == 0 || number == 'null') {
                    return { $valid: false }
                }else{
                    return { $valid: true }
                }

            } else {
                return { $valid: true }
            }


        }
    }
)

const useFormCompany = (formProps) => {
    const form = reactive(formProps);

    const store = useStore()
    const { makeToast } = useHelper();
    const { emit } = getCurrentInstance();
    const rules = {
        name: { required },
        country_id: {
            isDiferentZero: helpers.withMessage(
                "You must select an option",
                isDiferentZero
            ),
        },
        zip:{
            maxLength:maxLength(10)
        }
    };

    const v$ = useVuelidate(rules, form);

    const submitForm = async () => {
        try {
        
            const isFormCorrect = await v$.value.$validate();
            if (!isFormCorrect) return

            if(form.customer ==1){
                //Customer
                if(form.company_id == 'null' || form.company_id == null || form.company_id == undefined || form.company_id == 0){
                    makeToast('You must select a Distributor','error')
                    return
                }
            }
            let res
            if (form.id == 0)
                res = await axios.post('/company', form)
            else
                res = await axios.put(`/company/${form.id}`, form)
            const { ok, message, value } = res.data
            if (ok) {
                if (form.id == 0) {
                    const data = await store.dispatch("companies/getDistributors");
                    store.commit("companies/setDistributors", data);
                    store.commit('companies/addDataTable', value)
                    store.commit("companies/setFormCompany", 0);
                }
                else
                    store.commit('companies/updateItem', value)
                makeToast(message)
                emit('closeModal')
                emit('generateTable')
            } else {
                makeToast(message, 'error')
            }

        } catch (e) {
            makeToast('error', 'error')
        }
    }

    return {
        form,
        v$,
        submitForm
    }
}


export default useFormCompany