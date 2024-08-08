import * as VueI18n from "vue-i18n";
import en from '../lang/en';
import id from '../lang/id';
const { locale, locales } = window.config;

const i18n = VueI18n.createI18n({
    legacy: false,
    locale: 'id',
    fallbackLocale: 'en',
    messages: {
        en: en,
        id: id,
    },
});


export default i18n;