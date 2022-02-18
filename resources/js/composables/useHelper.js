import { useToast } from "vue-toastification";
const useHelper = () => {

    const toast = useToast();
    
    const getCurrentDateFormatT = () => {
        const currentDate = new Date();
        let fullMonth = '0';
        const dateFormated = '' + currentDate.getFullYear() + '-' + fullMonth + '-' + currentDate.toString().slice(8, 10) + 'T' + currentDate.toString().slice(16, 21);
        return dateFormated
    }
    const getCurrentDate = () => {
        const dateFormated = new Date().toISOString().slice(0, 10)
        return dateFormated
    }

    const makeToast = (message, type = "success") => {
        if (type == "success") {
            toast.success(message);
        }

        if (type == "info") {
            toast.info(message);
        }

        if (type == "error") {
            toast.error(message);
        }

        if (type == "warning") {
            toast.warning(message);
        }
    };

    return {
        toast,
        makeToast,
        getCurrentDate,
        getCurrentDateFormatT
    }


}


export default useHelper