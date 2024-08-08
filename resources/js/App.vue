<template>
    <el-config-provider>
        <Component :is="currentLayout" v-if="$route">
            <RouterView :key="$route.fullPath" />
        </Component>
  </el-config-provider>
</template>
<script setup>
import { useRoute, RouterView } from 'vue-router'
import BaseLayout from '@/Layouts/BaseLayout.vue';
import GuestLayout from '@/Layouts/GuestLayout.vue';
import { useAuthStore } from '@/stores/auth.js'
import { ref, watch, computed} from 'vue';
// Import `useAppBaseStore` explicitly for clarity
import { useAppBaseStore } from "@/stores/base";

watch(
  () => useAppBaseStore().isInitialized,
  (isInitialized) => {
    if (!isInitialized) {
      const appBase = useAppBaseStore().initApp();
    }
  },
  { immediate: true } // Call immediately on component creation
)

const currentLayout = computed(() => {
  const layouts = new Map([
    ['BaseLayout', BaseLayout],
    ['GuestLayout', GuestLayout],
  ])
  return layouts.get(`${useRoute().meta.layout || 'Base'}Layout`)
})
</script>