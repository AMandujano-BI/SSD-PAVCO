

import { required, helpers } from "@vuelidate/validators";
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
        console.log(param)
        const type = param.split('/')[1]
        const number = param.split('/')[0]
        if (type.toString() =='normal') {
            console.log('entro normal',value)
            console.log('entro normal number',number)

            if (parseInt(number) ==1) {
                console.log('entro a 1')
                if (value == undefined || value == null || value == 0) {
                    console.log('calv')
                    return { $valid: false }
                }
                return { $valid: true }
            } else {

                return { $valid: true }
            }
        } else {
            console.log('entro radio',value)
             console.log('company_id',number)

            if (value ==1) {
                console.log('number',number)
                if (number == undefined || number == null || number == 0) {
                    console.log('nose')
                    return { $valid: false }
                }
                    console.log('nose valido ')
                return { $valid: true }

            } else {
                console.log('entro a 0')
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
        company_id: {
            isCustomer: helpers.withMessage(
                "You must select an option",
                validationCustomer(form.customer + '/normal')


            )
        },
        customer: {
            isCustomer: helpers.withMessage(
                "You must select an option",
                validationCustomer(form.company_id + '/radio')
                // validationCustomerReverse(form.company_id)


            )

        }
    };

    const v$ = useVuelidate(rules, form);

    const submitForm = async () => {
        try {
            const isFormCorrect = await v$.value.$validate();
            if (!isFormCorrect) return
            let res
            console.log(form)
            return

            if (form.id == 0)
                res = await axios.post('/company', form)
            else
                res = await axios.put(`/company/${form.id}`, form)
            console.log(res.data)
            const { ok, message, value } = res.data
            if (ok) {
                if (form.id == 0) {
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
            console.log(e)
        }
    }

    return {
        form,
        v$,
        submitForm
    }
}


export default useFormCompany