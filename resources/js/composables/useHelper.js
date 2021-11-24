import { useToast } from "vue-toastification";
const useHelper = () => {

    const toast = useToast();
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
        makeToast
    }
    
}


export default useHelper