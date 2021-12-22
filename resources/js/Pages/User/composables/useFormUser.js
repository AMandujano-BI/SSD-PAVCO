import { reactive, computed, getCurrentInstance } from "vue";
import { required, helpers, sameAs, email } from "@vuelidate/validators";
import useHelper from "@/composables/useHelper";
import useVuelidate from "@vuelidate/core";
import { useStore } from "vuex";
const isDiferentZero = (value) => {
    return value != 0;
};
const useFormuser = (formProps) => {
    const form = reactive(formProps);
    const store = useStore()
    const { emit } = getCurrentInstance();
    const { makeToast } = useHelper();
    const rules = computed(() => ({
        username: { required },
        name: { required },
        password: { required },
        email: { required, email },
        confirm_password: { sameAsPassword: sameAs(form.password) },
        company_id: {
            isDiferentZero: helpers.withMessage(
                "You must select an option",
                isDiferentZero
            ),
        },
        rol_id: {
            isDiferentZero: helpers.withMessage(
                "You must select an option",
                isDiferentZero
            ),
        },
    }))
    const v$ = useVuelidate(rules, form);
    const submitForm = async () => {
        const isFormCorrect = await v$.value.$validate();
        if (!isFormCorrect) return

        const res = await store.dispatch('users/createUser', form)
        console.log(res)
        const { ok, message, value } = res
        if (ok) {
            if (form.id == 0) {
                // store.commit('companies/addDataTable', value)
                // store.commit("companies/setFormCompany", 0);
            }
            else
                // store.commit('companies/updateItem', value)
                makeToast(message)
            emit('closeModal')
            emit('generateTable')
        } else {
            makeToast(message, 'error')
        }
    }

    return {
        form,
        submitForm,
        v$
    }
}

export default useFormuser