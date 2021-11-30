

const useFormPart = () => {
    const form = ref({
        id: partUpdate.id,
        description: partUpdate.description,
        run_id: partUpdate.run_id,
        plateThick: partUpdate.plateThick,
        coatId: partUpdate.coatId,
        primaryCoatId: partUpdate.primaryCoatId,
        topCoatId: partUpdate.topCoatId,
        plate_types_id: partUpdate.plate_types_id,
        topCoatPer: partUpdate.topCoatPer,
        topCoatPer: partUpdate.topCoatPer,
        topCoatTemp: partUpdate.topCoatTemp,
        topCoatPH: partUpdate.topCoatPH,
        topCoatDiptime: partUpdate.topCoatDiptime,
        primaryPer: partUpdate.primaryPer,
        primaryTemp: partUpdate.primaryTemp,
        primaryPH: partUpdate.primaryPH,
        primaryDiptime: partUpdate.primaryDiptime,
        coatPer: partUpdate.coatPer,
        coatTemp: partUpdate.coatTemp,
        coatPH: partUpdate.coatPH,
        coatDiptime: partUpdate.coatDiptime,
    });
    const rules = {
        description: {
            required,
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
        primaryPer: {
            required,
        },
    };
    const v$ = useVuelidate(rules, form);

}


export default useFormPart