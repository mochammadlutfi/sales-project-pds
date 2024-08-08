<template>
    <div id="page-sidebar">

        <!-- Sidenav Brand Logo -->
        <router-link to="/dashboard" class="logo">
            <!-- Light Brand Logo -->
            <div class="logo-light">
                <img :src="app.logo_dark" class="logo-lg h-10" alt="Light logo">
                <img :src="app.logo_dark_sm" class="logo-sm" alt="Small logo">
            </div>

            <!-- Dark Brand Logo -->
            <div class="logo-dark">
                <img :src="app.logo_light" class="logo-lg h-10" alt="Dark logo">
                <img :src="app.logo_light_sm" class="logo-sm" alt="Small logo">
            </div>
        </router-link>
        
        <el-scrollbar class="scrollbar">
            <el-menu class="menu-ver" :collapse="isCollapse" router :default-active="$route.path">
                <template v-for="(node, index) in navigation" :key="`node-${index}`" >
                        <template v-if="node.sub">
                            <el-sub-menu :index="node.subActivePaths" popper-class="submenu-popper"  v-if="auth.hasPermission(node.permission)">
                                <template #title>
                                    <i :class="`${node.icon} el-icon`"></i>
                                    <span v-if="node.name" class="menu-text">{{ t(`${node.name}`) }}</span>
                                </template>
                                <template v-for="(su, si) in node.sub" :key="si">
                                    <el-menu-item :index="su.to" :route="su.to" v-if="auth.hasPermission(su.permission)">
                                        {{ t(`${su.name}`) }}
                                    </el-menu-item>
                                </template>
                            </el-sub-menu>
                        </template>
                        <template v-else>
                            <el-menu-item :index="node.to" :route="node.to" v-if="auth.hasPermission(node.permission)">
                                <i :class="`${node.icon} el-icon`"></i>
                                <template #title>
                                    <span v-if="node.name" class="menu-text">{{ t(node.name) }}</span>
                                </template>
                            </el-menu-item>
                        </template>
                    </template>
                <!-- </template> -->
            </el-menu>
        </el-scrollbar>
    </div>
</template>
<script setup>
import menuList from '@/menu.js';
import { useAppBaseStore } from '@/stores/base';
import { useAuthStore } from '@/stores/auth';
import { ref, computed } from 'vue';
import { useI18n } from 'vue-i18n';
const navigation = ref(menuList.main);
const auth = useAuthStore();
const appBase = useAppBaseStore();

const { t } = useI18n();
const app = computed(() => appBase.app);

const isCollapse = computed(() => appBase.settings.sidebarMini);

</script>
