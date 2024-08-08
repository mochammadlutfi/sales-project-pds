<template>
    <el-header id="page-header" class="flex items-center px-4 gap-3">
        <!-- Sidenav Menu Toggle Button -->
        <layout-modifier tag="button" id="button-toggle-menu" action="sidebarMiniToggle" class="hidden md:block nav-link p-2 me-auto">
            <span class="sr-only">Menu Toggle Button</span>
            <span class="flex items-center justify-center h-6 w-6">
                <i class="mgc_menu_line text-xl"></i>
            </span>
        </layout-modifier>

        
        <layout-modifier tag="button" id="button-toggle-menu" action="sidebarToggle" class="md:hidden nav-link p-2">
            <span class="sr-only">Menu Toggle Button</span>
            <span class="flex items-center justify-center h-6 w-6">
                <i class="mgc_menu_line text-xl"></i>
            </span>
        </layout-modifier>

        <!-- Topbar Brand Logo -->
        <a href="/" class="logo">
            <!-- Light Brand Logo -->
            <div class="logo-light">
                <img src="/images/logo-light.png" class="logo-lg h-6" alt="Light logo">
                <img src="/images/logo-sm.png" class="logo-sm" alt="Small logo">
            </div>

            <!-- Dark Brand Logo -->
            <div class="logo-dark">
                <img src="/images/logo-dark.png" class="logo-lg h-6" alt="Dark logo">
                <img src="/images/logo-sm.png" class="logo-sm" alt="Small logo">
            </div>
        </a>

        <!-- Language Dropdown Button -->
        <div class="relative">
            <el-dropdown trigger="click" @command="handleLocale">
                <button data-fc-type="dropdown" data-fc-placement="bottom-end" type="button" class="nav-link p-2 fc-dropdown">
                    <span class="flex items-center justify-center h-6 w-6">
                         <!-- <country-flag :country="locale" size='normal'/>
                         -->
                         {{  locale }}
                    </span>
                </button>
                <template #dropdown>
                    <el-dropdown-menu>
                        <el-dropdown-item v-for="(value, key) in locales" :key="key" :command="value.key" class="flex items-center gap-2.5 py-2 px-3">
                            <img :src="value.images" class="h-4 w-6">
                            <span class="align-middle">{{ value.name }}</span>
                        </el-dropdown-item>
                    </el-dropdown-menu>
                </template>
            </el-dropdown>
        </div>

        <!-- Light/Dark Toggle Button -->
        <div class="flex">
            <button id="light-dark-mode" type="button" class="nav-link p-2" @click.prevent="toggleDark()">
                <span class="sr-only">Light/Dark Mode</span>
                <span class="flex items-center justify-center h-6 w-6">
                    <i class=" text-2xl" :class="isDark"></i>
                </span>
            </button>
        </div>

        <!-- Profile Dropdown Button -->
        <div class="relative">
            <el-dropdown trigger="click" v-if="user">
                <button data-fc-type="dropdown" data-fc-placement="bottom-end" type="button">
                    <div class="flex">
                        <img :src="user.avatar_url" alt="user-image" class="rounded-md h-10">
                        <div class="md:block hidden my-auto ml-2 text-left">
                            <div class="font-semibold fs-md mb-0 leading-none text-[#536485] ">{{ user.name }}</div>
                            <span class="opacity-[0.7] fs-sm font-normal text-[#536485] block">{{ user.role }}</span>
                        </div>
                    </div>
                </button>
                <template #dropdown>
                    <el-dropdown-menu class="w-44 p-2 rounded-md">
                        <el-dropdown-item class="rounded">
                            <router-link to="/profile">
                                <i class="mgc_user_3_line text-md mr-4"></i>
                                <span>Profile</span>
                            </router-link>
                        </el-dropdown-item>
                        <el-dropdown-item class="rounded">
                            <router-link to="/profile/password">
                                <i class="mgc_lock_line text-md mr-4"></i>
                                <span>Password</span>
                            </router-link>
                        </el-dropdown-item>
                        <el-dropdown-item @click.prevent="logout" class="rounded">
                            <i class="mgc_exit_line text-md mr-4"></i>
                            <span>Log Out</span>
                        </el-dropdown-item>
                    </el-dropdown-menu>
                </template>
            </el-dropdown>
        </div>
    </el-header>
</template>
<script setup>
import LayoutModifier from '@/Components/LayoutModifier.vue';
import { useAppBaseStore } from '@/stores/base';
import { useAuthStore } from '@/stores/auth';
import { useDark, useToggle } from "@vueuse/core";
import { ref, computed } from 'vue';
import { useI18n } from 'vue-i18n';
import CountryFlag from 'vue-country-flag-next';

const { t, locale } = useI18n({ useScope: 'global' })

const appBase = useAppBaseStore();
const auth = useAuthStore();
const darkMode = useDark();
const locales = computed(() => useAppBaseStore().locales);
const user = computed(() => useAuthStore().user);
// console.log(locales);
// const app = computed(() => appBase.app);
const toggleDark = useToggle(darkMode);

const isDark = computed(() => (darkMode.value ? 'mgc_sun_line' : 'mgc_moon_line'));

const handleLocale = async (lang) => {
    locale.value = lang;
    appBase.setLocale(lang);
};

const logout = async () => {
  await auth.logout();
};


</script>
