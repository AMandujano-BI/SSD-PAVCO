import { required, helpers } from "@vuelidate/validators";
import { reactive, ref, getCurrentInstance } from "vue";
import useVuelidate from "@vuelidate/core";
import useHelper from "@/composables/useHelper";
import axios from "axios";

const isDiferentZero = (value) => {
    return value != 0 && value !=null;
};

const convertDate = (dateToConvert) => {
    const currentUTCDate = new Date(dateToConvert);
    const monthUTC = currentUTCDate.getUTCMonth() + 1;
    const dayUTC = currentUTCDate.getUTCDate();
    const hoursUTC = currentUTCDate.getUTCHours();
    const minutesUTC = currentUTCDate.getUTCMinutes();
    const secondsUTC = currentUTCDate.getUTCSeconds();
    let fullMonthUTC = "0";
    let fullDayUTC = "0";
    let fullHoursUTC = "0";
    let fullMinutesUTC = "0";
    let fullSecondsUTC = "0";
    monthUTC.toString().length < 2
        ? (fullMonthUTC = fullMonthUTC.concat(monthUTC))
        : (fullMonthUTC = monthUTC);
    dayUTC.toString().length < 2
        ? (fullDayUTC = fullDayUTC.concat(dayUTC))
        : (fullDayUTC = dayUTC);
    hoursUTC.toString().length < 2
        ? (fullHoursUTC = fullHoursUTC.concat(hoursUTC))
        : (fullHoursUTC = hoursUTC);
    minutesUTC.toString().length < 2
        ? (fullMinutesUTC = fullMinutesUTC.concat(minutesUTC))
        : (fullMinutesUTC = minutesUTC);
    secondsUTC.toString().length < 2
        ? (fullSecondsUTC = fullSecondsUTC.concat(secondsUTC))
        : (fullSecondsUTC = secondsUTC);

    const utcDate =
        "" +
        currentUTCDate.getUTCFullYear() +
        "-" +
        fullMonthUTC +
        "-" +
        fullDayUTC +
        "T" +
        fullHoursUTC +
        ":" +
        fullMinutesUTC +
        ":" +
        fullSecondsUTC;

    return utcDate;
}

const useFormReport = (formProps) => {
    const form = reactive(formProps);
    const loading = ref(false)
    const { makeToast } = useHelper();
    const { emit } = getCurrentInstance();

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
        
        form.startUtcDate = convertDate( `${form.start_date} 0:00:00` );
        form.endUtcDate = convertDate( `${form.endDate} 23:59:59` );


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

    return {
        form,
        v$,
        submitForm,
        loading
    }

}

export default useFormReport