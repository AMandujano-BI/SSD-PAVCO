import { required, helpers } from "@vuelidate/validators";
import { reactive, ref, getCurrentInstance } from "vue";
import useVuelidate from "@vuelidate/core";
import axios from "axios";
import useHelper from "@/composables/useHelper";

const isDiferentZero = (value) => {
    return value != 0;
};

const useFormReport = (formProps) => {
    const form = reactive(formProps);
    const { makeToast } = useHelper();
    const { emit } = getCurrentInstance();

    const rules = {
        name: { 
            required 
        },
        plate_type: {
            isDiferentZero: helpers.withMessage(
                'You must select an option',
                isDiferentZero
            ),
        },
        thickness: {
            isDiferentZero: helpers.withMessage(
                'You must select an option',
                isDiferentZero
            ),
        },
        chromate: {
            isDiferentZero: helpers.withMessage(
                'You must select an option',
                isDiferentZero
            ),
        },
        top_coat: {
            isDiferentZero: helpers.withMessage(
                'You must select an option',
                isDiferentZero
            ),
        },
        coat: {
            isDiferentZero: helpers.withMessage(
                'You must select an option',
                isDiferentZero
            ),
        }
    };

    const v$ = useVuelidate(rules, form);

    const submitForm = async () => {
        const isFormCorrect = await v$.value.$validate();
        console.log(form);
        // if (!isFormCorrect) return
        axios({
            url: '/report/runReportDetail/',
            method: 'post',
            data:form,
            // responseType: 'blob', // important
          }).then((response) => {
              console.log(response)
              return 
            const url = window.URL.createObjectURL(new Blob([response.data]));
            const link = document.createElement('a');
            link.href = url;
            link.setAttribute('download', 'file.pdf');
            document.body.appendChild(link);
            link.click();
          });
    }

    return {
        form,
        v$,
        submitForm
    }

}

export default useFormReport