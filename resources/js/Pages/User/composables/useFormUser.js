import { reactive } from "vue";
import { required, helpers } from "@vuelidate/validators";
import useHelper from "@/composables/useHelper";
import useVuelidate from "@vuelidate/core";
const isDiferentZero = (value) => {
    return value != 0;
};
const useFormuser = (formProps) => {
    const form = reactive(formProps);
    const { makeToast } = useHelper();
    const rules = {
        username: { required },
        firstname: { required },
        password: { required },
        confirm_password: { required },
        company_id: {
            isDiferentZero: helpers.withMessage(
                "You must select an option",
                isDiferentZero
            ),
        },
    };
    const v$ = useVuelidate(rules, form);
    const submitForm = async () => {
        console.log('submit')
        const isFormCorrect = await v$.value.$validate();
        if (!isFormCorrect) return
    }

    return {
        form,
        submitForm,
        v$
    }
}

export default useFormuser