import { defineStore } from 'pinia'
import Cookies from 'js-cookie';
import axios from 'axios';
const { locale, locales } = window.config;

const helpers = {
    getWindowWidth () {
      return window.innerWidth || document.documentElement.clientWidth || document.body.clientWidth
    },

    getCurrentYear () {
      return new Date().getFullYear()
    },

    getLocale (locales, fallback) {
        const locale = Cookies.get('locale')
        if (Object.prototype.hasOwnProperty.call(locales, 'id')) {
            return locale
        } else if (locale) {
            Cookies.remove('locale')
        }
            return fallback;
    }
}
const APP = "APP";
const getJSONFromLocalStorage = (key) => {
    const value = window.localStorage.getItem(key);

    if (value === 'undefined' || value === 'null' || value === undefined || value === null) {
        return null;
    }
    else {
        return JSON.parse(value);
    }
};


export const useAppBaseStore  = defineStore({
    id : 'appBase',
    state: () => {
        return {
            initialized : false,
            app: getJSONFromLocalStorage(APP),
            layout: {
              header: true,
              sidebar: true,
              sideOverlay: true,
              footer: true
            },
            settings: {
              colorTheme: '',
              sidebarLeft: true,
              sidebarMini: false,
              sidebarDark: true,
              sidebarVisibleDesktop: true,
              sidebarVisibleMobile: false,
              sideOverlayVisible: false,
              sideOverlayHoverable: false,
              pageOverlay: true,
              headerFixed: true,
              headerDark: false,
              headerSearch: false,
              headerLoader: false,
              pageLoader: false,
              rtlSupport: false,
              sideTransitions: true,
              mainContent: '' // 'boxed', ''narrow'
            }
        }
    },
    
    getters: {
        isInitialized(state){
            return (state.app);
        },
        locales :(state) => {
            return state.app.locales;
        },
        locale : (state) => {
            return state.app.locale;
        },
    },

    actions : {
        setLocale(locale) {
            this.app.locale = locale
            Cookies.set('locale', locale, { expires: 365 })
        },
        // Sets the layout, useful for setting different layouts (under layouts/variations/) 
        setLayout (payload) {
          state.layout.header = payload.header
          state.layout.sidebar = payload.sidebar
          state.layout.sideOverlay = payload.sideOverlay
          state.layout.footer = payload.footer
        },
        // Sets sidebar visibility (open, close, toggle)
        sidebar (mode) {
          if (helpers.getWindowWidth() > 991) {
            if (mode === 'open') {
              this.settings.sidebarVisibleDesktop = true
            } else if (mode === 'close') {
              this.settings.sidebarVisibleDesktop = false
            } else if (mode === 'toggle') {
              this.settings.sidebarVisibleDesktop = !this.settings.sidebarVisibleDesktop
            }
          } else {
            if (mode === 'open') {
              this.settings.sidebarVisibleMobile = true
            } else if (mode === 'close') {
              this.settings.sidebarVisibleMobile = false
            } else if (mode === 'toggle') {
              this.settings.sidebarVisibleMobile = !this.settings.sidebarVisibleMobile
            }
          }
        },
    
        // Sets sidebar mini mode (on, off, toggle)
        sidebarMini (mode) {
          if (helpers.getWindowWidth() > 991) {
            if (mode === 'on') {
              this.settings.sidebarMini = true
            } else if (mode === 'off') {
              this.settings.sidebarMini = false
            } else if (mode === 'toggle') {
              this.settings.sidebarMini = !this.settings.sidebarMini
            }
          }
        },

        // Sets sidebar position (left, right, toggle)
        sidebarPosition (mode) {
          if (mode === 'left') {
            this.settings.sidebarLeft = true
          } else if (mode === 'right') {
            this.settings.sidebarLeft = false
          } else if (mode === 'toggle') {
            this.settings.sidebarLeft = !this.settings.sidebarLeft
          }
        },

        // Sets sidebar style (dark, light, toggle)
        sidebarStyle (state, payload) {
          if (payload.mode === 'dark') {
            state.settings.sidebarDark = true
          } else if (payload.mode === 'light') {
            state.settings.sidebarDark = false
          } else if (payload.mode === 'toggle') {
            state.settings.sidebarDark = !state.settings.sidebarDark
          }
        },
        // Sets side overlay visibility (open, close, toggle)
        sideOverlay (state, payload) {
          if (payload.mode === 'open') {
            state.settings.sideOverlayVisible = true
          } else if (payload.mode === 'close') {
            state.settings.sideOverlayVisible = false
          } else if (payload.mode === 'toggle') {
            state.settings.sideOverlayVisible = !state.settings.sideOverlayVisible
          }
        },
        // Sets side overlay hover mode (on, off, toggle)
        sideOverlayHover (state, payload) {
          if (payload.mode === 'on') {
            state.settings.sideOverlayHoverable = true
          } else if (payload.mode === 'off') {
            state.settings.sideOverlayHoverable = false
          } else if (payload.mode === 'toggle') {
            state.settings.sideOverlayHoverable = !state.settings.sideOverlayHoverable
          }
        },
        // Sets page overlay visibility (on, off, toggle)
        pageOverlay (state, payload) {
          if (payload.mode === 'on') {
            state.settings.pageOverlay = true
          } else if (payload.mode === 'off') {
            state.settings.pageOverlay = false
          } else if (payload.mode === 'toggle') {
            state.settings.pageOverlay = !state.settings.pageOverlay
          }
        },
        // Sets header mode (fixed, static, toggle)
        header (state, payload) {
          if (payload.mode === 'fixed') {
            state.settings.headerFixed = true
          } else if (payload.mode === 'static') {
            state.settings.headerFixed = false
          } else if (payload.mode === 'toggle') {
            state.settings.headerFixed = !state.settings.headerFixed
          }
        },
        // Sets header style (dark, light, toggle)
        headerStyle (state, payload) {
          if (payload.mode === 'dark') {
            state.settings.headerDark = true
          } else if (payload.mode === 'light') {
            state.settings.headerDark = false
          } else if (payload.mode === 'toggle') {
            state.settings.headerDark = !state.settings.headerDark
          }
        },
        // Sets header search visibility (on, off, toggle)
        headerSearch (state, payload) {
          if (payload.mode === 'on') {
            state.settings.headerSearch = true
          } else if (payload.mode === 'off') {
            state.settings.headerSearch = false
          } else if (payload.mode === 'toggle') {
            state.settings.headerSearch = !state.settings.headerSearch
          }
        },
        // Sets header loader visibility (on, off, toggle)
        headerLoader (state, payload) {
          if (payload.mode === 'on') {
            state.settings.headerLoader = true
          } else if (payload.mode === 'off') {
            state.settings.headerLoader = false
          } else if (payload.mode === 'toggle') {
            state.settings.headerLoader = !state.settings.headerLoader
          }
        },
        // Sets page loader visibility (on, off, toggle)
        pageLoader (state, payload) {
          if (payload.mode === 'on') {
            state.settings.pageLoader = true
          } else if (payload.mode === 'off') {
            state.settings.pageLoader = false
          } else if (payload.mode === 'toggle') {
            state.settings.pageLoader = !state.settings.pageLoader
          }
        },
        // Sets main content mode (full, boxed, narrow)
        mainContent (state, payload) {
          if (payload.mode === 'full') {
            state.settings.mainContent = ''
          } else if (payload.mode === 'boxed') {
            state.settings.mainContent = 'boxed'
          } else if (payload.mode === 'narrow') {
            state.settings.mainContent = 'narrow'
          }
        },
        async initApp()
        {
            try {
                const response = await axios.get('/base');
                if(response.status == 200){
                    const data = response.data;
                    this.app = data;
                    window.localStorage.setItem(APP, JSON.stringify(data));
                }
            } catch (error) {
                console.error(error);
            }
        },

        async updateApp(){
            
        }
    }
})