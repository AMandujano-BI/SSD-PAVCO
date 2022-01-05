import { required, helpers } from "@vuelidate/validators";
import { reactive, ref, getCurrentInstance } from "vue";
import useVuelidate from "@vuelidate/core";
import useHelper from "@/composables/useHelper";
import axios from "axios";

const isDiferentZero = (value) => {
    return value != 0;
};

const useFormReport = (formProps) => {
    const form = reactive(formProps);
    const { makeToast } = useHelper();
    const { emit } = getCurrentInstance();

    const rules = {
        
        startDate: {
            isDiferentZero: helpers.withMessage(
                'You must select an option',
                isDiferentZero
            ),
        },
        endDate: {
            isDiferentZero: helpers.withMessage(
                'You must select an option',
                isDiferentZero
            ),
        },
      
    };

    const v$ = useVuelidate(rules, form);

    const submitForm = async () => {
        const isFormCorrect = await v$.value.$validate();
        if (!isFormCorrect) return
        axios.post('/report/runReportDetail',form,{
            Accept: 'application/pdf',
            responseType:'blob'
        }).then((response) => {
            const url = window.URL.createObjectURL(new Blob([response.data]));
            const link = document.createElement('a');
            link.href = url;
            link.setAttribute('download', 'file.pdf');
            document.body.appendChild(link);
            link.click();
        }).catch(e=>console.log(e))
    }

    return {
        form,
        v$,
        submitForm
    }

}

export default useFormReport