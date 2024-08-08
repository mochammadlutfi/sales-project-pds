<template>
    <div class="flex justify-between items-center mb-6">
        <h4 class="text-slate-900 dark:text-slate-200 text-lg font-medium">
            {{ pageTitle }}
        </h4>

        <div class="md:flex hidden items-center gap-2.5 text-sm font-semibold">
            <el-breadcrumb separator="/">
                <el-breadcrumb-item :to="{ path: '/' }">Dashboard</el-breadcrumb-item>
                <el-breadcrumb-item>{{pageTitle}}</el-breadcrumb-item>
            </el-breadcrumb>
        </div>
    </div>
    <el-card body-class="p-0">
        <el-form :model="form" @submit.prevent="onSubmit" label-position="top">
            <div class="p-4">
                <el-row :gutter="20">
                    <el-col :span="12">
                        <el-form-item :label="$t('name')" :error="errors.name">
                            <el-input v-model="form.name" />
                        </el-form-item>
                        <el-form-item :label="$t('email')" :error="errors.email">
                            <el-input v-model="form.email" />
                        </el-form-item>
                    </el-col>
                    <el-col :span="12">
                        <el-form-item :label="$t('phone')" :error="errors.phone">
                            <el-input v-model="form.phone">
                                <template #prepend>+62</template>
                            </el-input>
                        </el-form-item>
                        <el-form-item :label="$t('branch')" :error="errors.branch_id" v-if="user.role == 'Admin'">
                            <select-branch v-model="form.branch_id"/>
                        </el-form-item>
                    </el-col>
                </el-row>
            </div>

            <div class="p-4 float-end">
                <el-button type="danger" plain @click.prevent="$router.go(-1)">
                    <i class="mgc_close_fill me-2"></i>
                    {{ $t('cancel') }}
                </el-button>
                <el-button native-type="submit" type="primary">
                    <i class="mgc_check_fill me-2"></i>
                    {{ $t('save') }}
                </el-button>
            </div>
        </el-form>
    </el-card>
</template>

<script setup>
import SelectBranch from '@/Components/Form/SelectBranch.vue';
import { useRoute, useRouter } from 'vue-router';
import { ref, onMounted, computed } from 'vue';
import { useI18n } from 'vue-i18n';
import { useAuthStore } from '@/stores/auth';


const user = computed(() => useAuthStore().user);

const { t } = useI18n();
const route = useRoute()
const form = ref({
    id: route.params.id ? route.params.id : null,
    name: '',
    email : '',
    phone : '',
    branch_id : null,
});
const router = useRouter();
const errors = ref({});
const isLoading = ref(false);
const pageTitle = ref(`${ t('create') } ${t('sales')}`);

const fetchData = async () => {
    try {
        const response = await axios.get(`/sales/${route.params.id}`);
        if (response.status === 200) {
            const data = response.data;
            form.value.id = data.id;
            form.value.name = data.name;
            form.value.email = data.email;
            form.value.phone = data.phone;
            form.value.branch_id = data.branch_id;
        }
    } catch (error) {
        console.error(error);
    }
};

const onSubmit = async () => {
    isLoading.value = true;
    const url = form.value.id ?
        `/sales/${form.value.id}/update` :
        '/sales/store';
  try {
    const response = await axios.post(url, form.value);
    const data = response.data.result;
    router.push('/sales');
    ElMessage({
        message: t('success_msg'),
        type: 'success',
    });
  } catch (error) {
        errors.value = error.validation;
        ElMessage({
            message: t('error_msg'),
            type: 'error',
        });
  } finally {
    isLoading.value = false;
  }
};

onMounted(() => {
    if(route.params.id){
        pageTitle.value = `${ t('edit') } ${t('sales')}`;
        fetchData();
    }
})
</script>