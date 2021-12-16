

import { required, helpers } from "@vuelidate/validators";
import { reactive, ref } from "vue";
import useVuelidate from "@vuelidate/core";
import axios from "axios";
const isDiferentZero = (value) => {
    return value != 0;
};
const useFormCompany = (formProps) => {
    const form = reactive(formProps);
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
            const res = await axios.post('/company', form)
            console.log(res.data)

        } catch (e) {
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