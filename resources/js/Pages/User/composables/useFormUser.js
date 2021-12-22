import { reactive,computed } from "vue";
import { required, helpers,sameAs,email } from "@vuelidate/validators";
import useHelper from "@/composables/useHelper";
import useVuelidate from "@vuelidate/core";
const isDiferentZero = (value) => {
    return value != 0;
};
const useFormuser = (formProps) => {
    const form = reactive(formProps);
    const { makeToast } = useHelper();
    console.log(form.password)
    console.log(form)
    console.log(formProps)
    const rules =computed(() => ( {
        username: { required },
        firstname: { required },
        password: { required },
        email: { required,email },
        confirm_password: { sameAsPassword: sameAs(form.password)  },
        company_id: {
            isDiferentZero: helpers.withMessage(
                "You must select an option",
                isDiferentZero
            ),
        },
    }))
    const v$ = useVuelidate(rules, form);
    const submitForm = async () => {
        console.log('submit')
        console.log(form)
        console.log(form.password)
        console.log(form.confirm_password)
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