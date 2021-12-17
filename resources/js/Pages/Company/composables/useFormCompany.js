

import { required, helpers } from "@vuelidate/validators";
import { reactive, ref,getCurrentInstance } from "vue";
import useVuelidate from "@vuelidate/core";
import axios from "axios";
import useHelper from "@/composables/useHelper";
const isDiferentZero = (value) => {
    return value != 0;
};
const useFormCompany = (formProps) => {
    const form = reactive(formProps);
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
    };

    const v$ = useVuelidate(rules, form);

    const submitForm = async () => {
        try {

            const isFormCorrect = await v$.value.$validate();
            if (!isFormCorrect) return
            console.log(form)
            const res = await axios.post('/company', form)
            console.log(res.data)

            const { ok, message, value } = res.data
            if (ok) {
                makeToast(message)
                emit('closeModal')
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