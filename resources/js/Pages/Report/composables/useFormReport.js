import { required, helpers } from "@vuelidate/validators";
import { reactive, ref, getCurrentInstance } from "vue";
import useVuelidate from "@vuelidate/core";
import useHelper from "@/composables/useHelper";
import axios from "axios";

const isDiferentZero = (value) => {
    return value != 0 && value !=null;
};

const useFormReport = (formProps) => {
    const form = reactive(formProps);
    const loading = ref(false)
    const { makeToast } = useHelper();
    const { emit } = getCurrentInstance();
    const filterOption = ref(3);
    const rules = {

        start_date: {
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
        primaryPer_more_than: { required },
        primaryPer_less_than: { required },
        primaryTemp_more_than: { required },
        primaryTemp_less_than: { required },
        primaryPH_more_than: { required },
        primaryPH_less_than: { required },
        primaryDiptime_more_than: { required },
        primaryDiptime_less_than: { required },

        topCoatPer_more_than: { required },
        topCoatPer_less_than: { required },
        topCoatTemp_more_than: { required },
        topCoatTemp_less_than: { required },
        topCoatPH_more_than: { required },
        topCoatPH_less_than: { required },
        topCoatDiptime_more_than: { required },
        topCoatDiptime_less_than: { required },

        coatPer_more_than: { required },
        coatPer_less_than: { required },
        coatTemp_more_than: { required },
        coatTemp_less_than: { required },
        coatPH_more_than: { required },
        coatPH_less_than: { required },
        coatDiptime_more_than: { required },
        coatDiptime_less_than: { required },

    };

    const v$ = useVuelidate(rules, form);

    const submitForm = async () => {
        form.filterOption = parseInt( filterOption.value)
        const isFormCorrect = await v$.value.$validate();
        if (!isFormCorrect) return
        loading.value = true
        axios.post('/report/runReportDetail', form, {
            Accept: 'application/pdf',
            responseType: 'blob'
        }).then((response) => {
            loading.value = false
            const url = window.URL.createObjectURL(new Blob([response.data]));
            const link = document.createElement('a');
            link.href = url;
            link.setAttribute('download', 'Run_Report.pdf');
            document.body.appendChild(link);
            link.click();
        }).catch(e => {
            loading.value = false
        })
    }

    const exportCSV = async () => {
        form.filterOption = parseInt(filterOption.value);
        const isFormCorrect = await v$.value.$validate();
        if (!isFormCorrect) return;
    
        try {
          const response = await axios.post("/report/runReportDetailCSV", form, {
            responseType: "blob",
          });
          const url = window.URL.createObjectURL(new Blob([response.data]));
          const link = document.createElement("a");
          link.href = url;
          link.setAttribute("download", "Run_Report.csv");
          document.body.appendChild(link);
          link.click();
        } catch (error) {
          console.log("Error exporting CSV:", error);
        }
    };

    return {
        form,
        v$,
        submitForm,
        exportCSV,
        loading,
        filterOption,
    }

}

export default useFormReport
