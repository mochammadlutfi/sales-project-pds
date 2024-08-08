
import { computed } from 'vue';
import { useAuthStore } from '../stores/auth';
import { useAppBaseStore } from '../stores/base';
import dayjs from 'dayjs';
import 'dayjs/locale/en';
import 'dayjs/locale/id';


export function useGeneralHelper() {
    const authStore = useAuthStore();
    const app = useAppBaseStore();

    function dateFormat(date){
        return dayjs(date).locale('id').format('DD MMM YYYY');
    }

    return {
        dateFormat
    };
}