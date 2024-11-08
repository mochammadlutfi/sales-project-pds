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
                        <el-form-item :label="$t('project.address')" :error="errors.address">
                            <el-input type="textarea" :rows="2" v-model="form.address" />
                        </el-form-item>
                        <el-form-item :label="$t('project.amount')" :error="errors.sales">
                            <el-input
                                v-model="form.amount"
                                placeholder="Masukan Harga Beli"
                                :formatter="(value) => `Rp ${value}`.replace(/\B(?=(\d{3})+(?!\d))/g, ',')"
                                :parser="(value) => value.replace(/^Rp\s+|(\,)/gi, '')"
                            />
                        </el-form-item>
                        <el-form-item :label="$t('project.customer_name')" :error="errors.customer_name">
                            <el-input v-model="form.customer_name" />
                        </el-form-item>
                        <el-form-item :label="$t('project.customer_phone')" :error="errors.customer_phone">
                            <el-input v-model="form.customer_phone" />
                        </el-form-item>
                        <el-form-item :label="$t('project.customer_email')" :error="errors.customer_email">
                            <el-input v-model="form.customer_email" />
                        </el-form-item>
                    </el-col>
                    <el-col :span="12">
                        <el-form-item :label="$t('project.cp_name')" :error="errors.cp_name">
                            <el-input v-model="form.cp_name" />
                        </el-form-item>
                        <el-form-item :label="$t('project.cp_phone')" :error="errors.cp_phone">
                            <el-input v-model="form.cp_phone" />
                        </el-form-item>
                        <el-form-item :label="$t('project.cp_position')" :error="errors.cp_position">
                            <el-input v-model="form.cp_position" />
                        </el-form-item>
                        <el-form-item :label="$t('branch')" :error="errors.branch_id" v-if="user.role == 'Admin'">
                            <select-branch v-model="form.branch_id"/>
                        </el-form-item>
                        <el-form-item :label="$t('sales_person')" :error="errors.sales">
                            <select-sales v-model="form.sales_id"/>
                        </el-form-item>
                        <el-form-item label="Status" :error="errors.status">
                            <el-select v-model="form.status" placeholder="Pilih Status">
                                <el-option label="Draft" value="Draft"/>
                                <el-option label="Follow Up" value="Follow Up"/>
                                <el-option label="Done" value="Done"/>
                            </el-select>
                        </el-form-item>
                        <el-form-item :label="$t('project.link_map')" :error="errors.cp_position">
                            <el-input v-model="form.link_map" />
                        </el-form-item>
                        <el-form-item>
                            <el-checkbox v-model="form.is_ready" :label="$t('project.is_ready')" size="large" />
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
import SelectSales from '@/Components/Form/SelectSales.vue';
import { useRoute,useRouter } from 'vue-router';
import { ref, computed, onMounted } from 'vue';
import { useI18n } from 'vue-i18n';
import { useAuthStore } from '@/stores/auth';


const user = computed(() => useAuthStore().user);
const { t } = useI18n();
const route = useRoute();
const router = useRouter();
const form = ref({
    id: route.params.id ? route.params.id : null,
    name: '',
    customer_name : '',
    customer_phone : '',
    customer_email : '',
    customer_address : '',
    cp_name : '',
    cp_position : '',
    cp_phone : '',
    sales_id : null,
    branch_id : null,
    amount : '',
    is_ready : false,
    status : '',
    link_map : ''
});
const errors = ref({});
const isLoading = ref(false);
const pageTitle = ref(`${ t('create') } ${t('project.title')}`);

const fetchData = async () => {
    try {
        const response = await axios.get(`/project/${route.params.id}`);
        if (response.status === 200) {
            const data = response.data;
            form.value.id = data.id;
            form.value.name = data.name;
            form.value.address = data.address;
            form.value.customer_name = data.customer_name;
            form.value.customer_phone = data.customer_phone;
            form.value.customer_email = data.customer_email;
            form.value.cp_name = data.cp_name;
            form.value.cp_phone = data.cp_phone;
            form.value.cp_position = data.cp_position;
            form.value.sales_id = data.sales_id;
            form.value.branch_id = data.branch_id;
            form.value.amount = data.amount;
            form.value.is_ready = data.is_ready;
            form.value.status = data.status;
            form.value.link_map = data.link_map;
        }
    } catch (error) {
        console.error(error);
    }
};

const onSubmit = async () => {
    isLoading.value = true;
    const url = form.value.id ?
        `/project/${form.value.id}/update` :
        '/project/store';
  try {
    const response = await axios.post(url, form.value);
    const project_id = response.data.result.id;

    router.push({name : 'project.show', params: { project_id } });
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
        pageTitle.value = `${ t('edit') } ${t('project.title')}`;
        fetchData();
    }
})
</script>