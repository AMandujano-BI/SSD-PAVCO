
import useHelper from "@/composables/useHelper";
import { Inertia } from '@inertiajs/inertia';
import useVuelidate from "@vuelidate/core";
import { helpers, minValue, required } from "@vuelidate/validators";
import axios from "axios";
import { reactive, ref } from "vue";
const isDiferentZero = (value) => {
    return value != 0 && value !=null;
};
const useFormRun = () => {
    const loading = ref(false)
    const { makeToast,getCurrentDate } = useHelper();
    const currentDate = new Date();
    let month = currentDate.getMonth() + 1;
    let fullMonth = '0';
    (month.toString().length < 2) ? fullMonth = fullMonth.concat(month) : fullMonth = month;
    const dateFormated = getCurrentDate()
   
    const form = reactive({
        id: 0,
        number: 0,
        start_date: dateFormated,
        description: "",
        status: 0,
        idCustomer: 0,
        user_id: 0,
        plate_methods_id: 0,
        company_id: 0,
        parts: [
            {
                coatId: 0,
                plate_types_id: 0,
                topCoatId: 0,
                primaryCoatId: 0,
                plateThick: '',
                typePlateThick: 0,
                numberParts: 1,
                //------Details
                topCoatPer: '',
                topCoatTemp: '',
                topCoatPH: '',
                topCoatDiptime: '',
                primaryPer: '',
                primaryTemp: '',
                primaryPH: '',
                primaryDiptime: '',
                coatPer: '',
                coatTemp: '',
                coatPH: '',
                coatDiptime: '',
            }

        ],

    });
    const addForm = () => {
        form.parts.push({
            coatId: 0,
            plate_types_id: 0,
            topCoatId: 0,
            primaryCoatId: 0,
            plateThick: '',
            typePlateThick:0,
            numberParts: 1,
            //------Details
            topCoatPer: '',
            topCoatTemp: '',
            topCoatPH: '',
            topCoatDiptime: '',
            primaryPer: '',
            primaryTemp: '',
            primaryPH: '',
            primaryDiptime: '',
            coatPer: '',
            coatTemp: '',
            coatPH: '',
            coatDiptime: '',
        })
    }

    const rules = {
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

        start_date: { required, },
        parts: {
            $each: helpers.forEach({
                numberParts: {
                    required,
                    minValue: minValue(1),
                },
          


            })
        }
        // Details
        // topCoatPer: { required, },
        // topCoatTemp: { required, },
        // topCoatPH: { required, },
        // topCoatDiptime: { required, },
        // primaryPer: { required, },
        // primaryTemp: { required, },
        // primaryPH: { required, },
        // primaryDiptime: { required, },
        // coatPH: { required },
        // coatPer: { required },
        // coatTemp: { required },
        // coatDiptime: { required }

    };
    const v$ = useVuelidate(rules, form,{ $lazy: true });
    const submitForm = async () => {
        const isFormCorrect = await v$.value.$validate();

        if (!isFormCorrect) return;
        try {
            let res;
            loading.value = true

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


 

    const deleteSection =(index) =>{
        form.parts.splice(index,1)
    }
    return {
        form,
        v$,
        submitForm,
        loading,
        addForm,
        deleteSection,

    }
}
export default useFormRun