
import { required, helpers, minValue } from "@vuelidate/validators";
import useVuelidate from "@vuelidate/core";
import useHelper from "@/composables/useHelper";
import axios from "axios";
import { ref, reactive } from "vue";
import { Inertia } from '@inertiajs/inertia'
const isDiferentZero = (value) => {
    return value != 0;
};
const useFormRun = () => {
    const loading = ref(false)
    const { makeToast } = useHelper();
    const currentDate = new Date();
    let month = currentDate.getMonth()+1;
    let fullMonth = '0';
    (month.toString().length < 2) ? fullMonth = fullMonth.concat(month) : fullMonth = month;
    const dateFormated = ''+currentDate.getFullYear()+'-'+fullMonth+'-'+currentDate.toString().slice(8,10)+'T'+currentDate.toString().slice(16,21);
    const options = ref([
        {
            value: '1', label: 'fdas'
        }
    ])
    const form = reactive({
        id: 0,
        number: 0,
        start_date: dateFormated,
        description: "",
        status: 0,
        idCustomer: 0,
        user_id: 0,
        company_id: 0,
        plate_methods_id: 0,
        coatId: 0,
        primaryCoatId: 0,
        topCoatId: 0,
        plate_types_id: 0,
        plateThick: 0,
        //------Details
        topCoatPer: 0,
        topCoatTemp: 0,
        topCoatPH: 0,
        topCoatDiptime: 0,
        primaryPer: 0,
        primaryTemp: 0,
        primaryPH: 0,
        primaryDiptime: 0,
        coatPer: 0,
        coatTemp: 0,
        coatPH: 0,
        coatDiptime: 0,
        numberParts: 0,
    });

    const rules = {
        description: {
            required,
        },
        start_date: {
            required,
        },
        plate_methods_id: {
            isDiferentZero: helpers.withMessage(
                "You must select an option",
                isDiferentZero
            ),
        },
        company_id: {
            isDiferentZero: helpers.withMessage(
                "You must select an option",
                isDiferentZero
            ),
        },
        plate_types_id: {
            isDiferentZero: helpers.withMessage(
                "You must select an option",
                isDiferentZero
            ),
        },
        primaryCoatId: {
            isDiferentZero: helpers.withMessage(
                "You must select an option",
                isDiferentZero
            ),
        },
        topCoatId: {
            isDiferentZero: helpers.withMessage(
                "You must select an option",
                isDiferentZero
            ),
        },
        coatId: {
            isDiferentZero: helpers.withMessage(
                "You must select an option",
                isDiferentZero
            ),
        },
        numberParts: {
            required,
            minValue: minValue(1)
        },
        start_date: { required, },
        topCoatPer: { required, },
        topCoatTemp: { required, },
        topCoatPH: { required, },
        topCoatDiptime: { required, },
        primaryPer: { required, },
        primaryTemp: { required, },
        primaryPH: { required, },
        primaryDiptime: { required, },
        plateThick: { required },
        coatPH: { required },
        coatPer: { required },
        coatTemp: { required },
        coatDiptime: { required }

    };
    const v$ = useVuelidate(rules, form);
    const submitForm = async () => {
        try {
            const isFormCorrect = await v$.value.$validate();
            if (!isFormCorrect) return;
            let res;
            loading.value = true
            
            const currentDate = new Date(form.start_date);
            let month = currentDate.getUTCMonth()+1;
            let day = currentDate.getUTCDay()+2;
            let fullMonth = '0';
            let fullDay = '0';
            (month.toString().length < 2) ? fullMonth = fullMonth.concat(month) : fullMonth = month;
            (day.toString().length < 2) ? fullDay = fullDay.concat(day) : fullDay = day;
            const startUTCDate = 
                ''+
                currentDate.getUTCFullYear()+
                '-'+
                fullMonth+
                '-'+
                fullDay+
                'T'+
                currentDate.getUTCHours()+
                ':'+
                currentDate.getUTCMinutes();
            form.start_date = startUTCDate;

            res = await axios.post(`/run/`, form);
            const { ok, value, message } = res.data;
            loading.value = false
            if (ok) {
                makeToast(message);
                // window.location.href = `/part/${value.id}`;
                Inertia.get(`/part/${value.id}`)
            } else {
                makeToast("An error has occurred", "error");
            }
        } catch (e) {
            loading.value = false
            console.log(e);
            makeToast("An error has occurred", "error");
        }
    };

    const changeDropdown = (e) => {
        console.log(e)
    }
    return {
        form,
        v$,
        submitForm,
        loading,
        options,
        changeDropdown

    }
}
export default useFormRun