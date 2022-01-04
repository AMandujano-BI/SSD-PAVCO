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
        customer: {
            isDiferentZero: helpers.withMessage(
                'You must select an option',
                isDiferentZero
            ),
        },
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
        chromate: {
            isDiferentZero: helpers.withMessage(
                'You must select an option',
                isDiferentZero
            ),
        },
        plate_type: {
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
        },
        //COAT
        coatPer_less_than: { required },
        coatPer_more_than: { required },
        coatTemp_less_than: { required },
        coatTemp_more_than: { required },
        coatPH_less_than: { required },
        coatPH_more_than: { required },
        coatDiptime_less_than: { required },
        coatDiptime_more_than: { required },
        //TOPCOAT
        topCoatPer_less_than: { required },
        topCoatPer_more_than: { required },
        topCoatTemp_less_than: { required },
        topCoatTemp_more_than: { required },
        topCoatPH_less_than: { required },
        topCoatPH_more_than: { required },
        topCoatDiptime_less_than: { required },
        topCoatDiptime_more_than: { required },
        //chromate
        primaryPer_less_than: { required },
        primaryPer_more_than: { required },
        primaryTemp_less_than: { required },
        primaryTemp_more_than: { required },
        primaryPH_less_than: { required },
        primaryPH_more_than: { required },
        primaryDiptime_less_than: { required },
        primaryDiptime_more_than: { required },
    };

    const v$ = useVuelidate(rules, form);

    const submitForm = async () => {
        const isFormCorrect = await v$.value.$validate();
        if (!isFormCorrect) return
        axios({
            url: '/report/runReportDetail/',
            method: 'post',
            data: form,
            // responseType: 'blob', // important
        }).then((response) => {
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