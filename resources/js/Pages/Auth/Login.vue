<template>
    <div class="2xl:w-1/4 lg:w-1/3 md:w-1/2 w-full">
        <el-card class="overflow-hidden sm:rounded-md rounded-none">
            <div class="p-6">
                <a href="" class="block mb-8">
                    <img class="w-full block dark:hidden" :src="app.logo_light" alt="">
                    <img class="w-full hidden dark:block" :src="app.logo_dark" alt="">
                </a>

                <el-form label-position="top" label-width="100%" @submit.prevent="submit">
                    <el-form-item label="Username / Email" :error="errors.email">
                        <el-input
                            v-model="form.email"
                            type="text"
                        />
                    </el-form-item>
                    <el-form-item label="Password" :error="errors.password">
                        <el-input
                            v-model="form.password"
                            type="password"
                            show-password
                        />
                    </el-form-item>
                    <el-button native-type="submit" type="primary" class="w-full" :loading="loading">
                        Login
                    </el-button>
                </el-form>
            </div>
        </el-card>
    </div>
</template>
<script setup>

import { useRouter, RouterView } from 'vue-router';
import { useAuthStore } from '@/stores/auth.js';
import { useAppBaseStore } from "@/stores/base";
import  { ref, computed } from 'vue';

const form = ref({
  email: "",
  password: "",
});

const errors = ref({});
const loading = ref(false);
const router = useRouter();

const authStore = useAuthStore();
const appBase = useAppBaseStore();
const app = computed(() => appBase.app);

const submit = async () => {
    loading.value = true;
    try {
        const response = await axios.post('/login', form.value);
        const data = response.data.result;
        authStore.updateToken(data.access_token);
        authStore.updateUser(data.user);
        authStore.updatePermissions(data.permissions);
        router.push('/dashboard');
    } catch (error) {
        errors.value = error.validation;
    } finally {
        loading.value = false;
    }
};

</script>