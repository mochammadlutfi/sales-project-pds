<template>
    <el-config-provider>
        <div id="page-wrapper" class="flex wrapper" :class="classContainer">

            <base-sidebar/>

            <div class="page-content">
                <!-- <base-header/> -->
                <el-main id="main-container" class="flex-grow p-6">
                    <slot />
                </el-main>
            </div>

        </div>
    </el-config-provider>
</template>
  
<script>

    import BaseHeader from './Partials/BaseHeader.vue';
    import { useAppBaseStore } from '@/stores/base';
    export default {
        name : 'AuthenticatedLayout',
        components: {
            BaseHeader,
            BaseSidebar,
            ElConfigProvider,
        },
        data() {
            return {
                appBase : useAppBaseStore(),
            }
        },
        computed:{
            classContainer () {
                return {
                    'sidebar-r': this.appBase.layout.sidebar && !this.appBase.settings.sidebarLeft,
                    'sidebar-mini': this.appBase.layout.sidebar && this.appBase.settings.sidebarMini,
                    'sidebar-o':  this.appBase.layout.sidebar && this.appBase.settings.sidebarVisibleDesktop,
                    'sidebar-o-xs': this.appBase.layout.sidebar && this.appBase.settings.sidebarVisibleMobile,
                    'sidebar-dark': this.appBase.layout.sidebar && this.appBase.settings.sidebarDark,
                    'side-overlay-o': this.appBase.layout.sideOverlay && this.appBase.settings.sideOverlayVisible,
                    'side-overlay-hover': this.appBase.layout.sideOverlay && this.appBase.settings.sideOverlayHoverable,
                    'enable-page-overlay': this.appBase.layout.sideOverlay && this.appBase.settings.pageOverlay,
                    'page-header-fixed': this.appBase.layout.header && this.appBase.settings.headerFixed,
                    'page-header-dark': this.appBase.layout.header && this.appBase.settings.headerDark,
                    'main-content-boxed': this.appBase.settings.mainContent === 'boxed',
                    'main-content-narrow': this.appBase.settings.mainContent === 'narrow',
                    'rtl-support': this.appBase.settings.rtlSupport,
                    'side-trans-enabled': this.appBase.settings.sideTransitions,
                    'side-scroll': true
                }
            }
        },
    }
  </script>@/stores/base