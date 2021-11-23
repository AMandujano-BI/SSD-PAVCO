
import { required, helpers,minValue } from "@vuelidate/validators";
import useVuelidate from "@vuelidate/core";
// import {useToast} from "vue-toast-notification";
import axios from "axios";
import { ref, reactive } from "vue";
const isDiferentZero = (value) => {
    return value != 0;
  };
 const useFormRun = () => {
    const form = reactive({
        id: 0,
        number: 0,
        startDate: new Date().toISOString().slice(0, 10),
        description: "",
        status: 0,
        idCustomer: 0,
        user_id: 0,
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
        startDate: {
            required,
        },
        plate_methods_id: {
            isDiferentZero: helpers.withMessage(
                "You must select an option",
                isDiferentZero
            ),
        },
        user_id: {
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
            minValue:1
        },
        startDate: {
            required,
        },
        topCoatPer: {
            required,
        },
        topCoatTemp: {
            required,
        },
        topCoatPH: {
            required,
        },
        topCoatDiptime: {
            required,
        },
        primaryPer: {
            required,
        },
    };
    const v$ = useVuelidate(rules, form);
    const submitForm = async () => {
        try {
          const isFormCorrect = await v$.value.$validate();
          console.log(form)
          return
          if (!isFormCorrect) return;
          let res;
          res = await axios.post(`/run/`, form);
          const { ok, value, message } = res.data;
  
          console.log(res.data);
          if (ok) {
            window.location.href = `/part/${value.id}`;
            // makeToast("Run was created successfully");
          } else {
            console.log(res.data);
            // makeToast("An error has occurred", "error");
          }
        } catch (e) {
          console.log(e);
          // makeToast("An error has occurred", "error");
        }
      };
      
    // const makeToast = (message, type = "success") => {
    //     toast.open({
    //       message: message,
    //       type: type,
    //       duration: 5000,
    //       dismissible: true,
    //     });
    //   };
    return {
        form,
        v$,
        submitForm

    }
}
export default useFormRun