<template>    
    <div class="flex justify-between items-center mb-6">
        <h4 class="text-slate-900 dark:text-slate-200 text-lg font-medium">{{ $t('branch') }}</h4>

        <div class="md:flex hidden items-center gap-2.5 text-sm font-semibold">
            <el-breadcrumb separator="/">
                <el-breadcrumb-item :to="{ path: '/' }">{{ $t('dashboard') }}</el-breadcrumb-item>
                <el-breadcrumb-item :to="{ path: '/settings' }">{{ $t('settings') }}</el-breadcrumb-item>
                <el-breadcrumb-item>{{ $t('branch') }}</el-breadcrumb-item>
            </el-breadcrumb>
        </div>
    </div>
    
    <el-card body-class="p-0">
        <div class="flex justify-between items-center p-4">
            <el-select v-model="params.limit" placeholder="Pilih" style="width: 115px" @change="fetchData(1)">
                <el-option label="25" value="25"/>
                <el-option label="50" value="50"/>
                <el-option label="100" value="100"/>
            </el-select>

            <div class="flex items-center gap-2">
                <el-button type="primary" @click.prevent="filterShow = !filterShow" :plain="filterShow">
                    <i class="mgc_filter_line me-2"></i>
                    Filter
                </el-button>
                <el-button type="primary" @click.prevent="create" v-if="auth.hasPermission('branch.create')">
                    <i class="mgc_add_line me-2"></i>
                    {{ $t('create') }}
                </el-button>
            </div>
        </div>
        <transition name="slide-down">
            <div class="card-filter p-4" v-if="filterShow">
                <el-form label-position="top" @submit.prevent="fetchData">
                    <el-row :gutter="20">
                        <el-col :md="12">
                            <el-form-item :label="$t('name')">
                                <el-input v-model="params.name" />
                            </el-form-item>
                        </el-col>
                        <el-col :md="12">
                            <el-form-item :label="$t('address')">
                                <el-input v-model="params.address" />
                            </el-form-item>
                        </el-col>
                    </el-row>
                    
                    <div class="flex items-center gap-2">
                        <el-button type="primary" native-type="submit">
                            <i class="mgc_search_line me-2"></i>
                            {{ $t("search") }}
                        </el-button>
                        <el-button type="primary" @click.prevent="resetFilter">
                            <i class="mgc_refresh_3_line me-2"></i>
                            Reset
                        </el-button>
                    </div>
                </el-form>
            </div>
        </transition>
        <el-table class="min-w-full" 
            :data="dataList" v-loading="loading">
            <el-table-column prop="name" :label="$t('name')" header-align="center"/>
            <el-table-column prop="address" :label="$t('address')" header-align="center"/>
            <el-table-column :label="$t('action')" align="center" width="100">
                <template #default="scope">
                    <el-dropdown popper-class="dropdown-action" trigger="click" v-if="auth.hasPermission('branch.delete') || auth.hasPermission('branch.update')">
                        <el-button type="primary">
                        {{ $t('action') }}
                        </el-button>
                        <template #dropdown>
                            <el-dropdown-menu>
                                <el-dropdown-item @click.prevent="onEdit(scope.row.id)" v-if="auth.hasPermission('branch.update')">
                                    <i class="mgc_edit_line"></i>
                                    {{  $t('edit') }}
                                </el-dropdown-item>
                                <el-dropdown-item @click.prevent="onDelete(scope.row.id)" v-if="auth.hasPermission('branch.delete')">
                                    <i class="mgc_delete_2_line"></i>
                                    {{ $t('delete') }}
                                </el-dropdown-item>
                            </el-dropdown-menu>
                        </template>
                    </el-dropdown>
                </template>
            </el-table-column>
        </el-table>
        <div class="flex justify-between items-center p-4">
            <div class="flex items-center gap-2">
                <p class="mb-0">{{ $t('tablePaginate', { from: params.from, to: params.to, total:params.total }) }}</p>
            </div>
            <div class="flex items-center gap-2">
                <el-pagination class="float-end" background layout="prev, pager, next"  
                    :page-size="params.pageSize" 
                    :total="params.total" 
                    :current-page="params.page" 
                    @current-change="fetchData"
                />
            </div>
        </div>
    </el-card>
    <el-dialog
    v-model="modalForm"
    :title="modalTitle"
    class="rounded-md">
        <el-form :model="form" @submit.prevent="onSubmit" label-position="top">
            <el-form-item :label="$t('name')" :error="errors.name">
                <el-input v-model="form.name" />
            </el-form-item>
            <el-form-item :label="$t('address')">
                <el-input type="textarea" v-model="form.address" />
            </el-form-item>
            <div class="text-right">
                <el-button type="danger" plain @click.prevent="modalForm = false">
                    <i class="mgc_close_fill me-2"></i>
                    {{ $t('cancel') }}
                </el-button>
                <el-button native-type="submit" type="primary">
                    <i class="mgc_check_fill me-2"></i>
                    {{ $t('save') }}
                </el-button>
            </div>
        </el-form>
    </el-dialog>
</template>
<script setup>
import { ref, reactive, onMounted } from 'vue';
import SelectUser from "@/Components/Form/SelectUser.vue";
import axios from 'axios';
import { ElMessage, ElMessageBox } from 'element-plus';
import { useAuthStore } from '@/stores/auth';
const auth = useAuthStore();

const loading = ref(false);
const dataList = ref([]);
const params = ref({
    page: 1,
    limit: 25,
    from: 0,
    to: 0,
    total: 0,
    pageSize: 0,
    name: null,
    manager_id: null,
    address: null,
});
const filterShow = ref(false);
const modalTitle = ref(''); // Initialize with empty string or a default value
const modalForm = ref(false);
const form = reactive({
    id: null,
    name: null,
    manager_id: null,
    address: null,
});
const errors = ref({});

// Convert methods to functions
const resetFilter = () => {
    params.name = null;
    params.address = null;
    fetchData(1);
};

console.log(auth.hasPermission('branch.create'));

const fetchData = async (page = 1) => {
    try {
        errors.value = {};
        loading.value = true;
        const response = await axios.get('/settings/branch', { params : params.value });
        if (response.status === 200) {
            dataList.value = response.data.data;
            params.value.from = response.data.from;
            params.value.to = response.data.to;
            params.value.page = response.data.current_page;
            params.value.total = response.data.total;
            params.value.pageSize = response.data.per_page;
        }
        loading.value = false;
    } catch (error) {
        console.error(error);
    }
};

const onSubmit = async () => {
    loading.value = true;
    const url = form.id ? `/settings/branch/${form.id}/update` : '/settings/branch/store';
    try {
        const response = await axios.post(url, form);
        console.log(response.data.result);
        ElMessage({ message: 'Success Message', type: 'success' });
        modalForm.value = false;
        resetForm();
        fetchData();
    } catch (error) {
        errors.value = error.validation;
        ElMessage({ message: 'Error Message', type: 'error' });
    } finally {
        loading.value = false;
    }
};

const create = () => {
    modalForm.value = true;
};

const resetForm = () => {
    form.id = null;
    form.name = null;
    form.address = null;
};

const onEdit = async (id) => {
    try {
        const response = await axios.get(`/settings/branch/${id}`);
        console.log(response.data);
        Object.assign(form, response.data);
        modalTitle.value = 'Edit Branch';
        modalForm.value = true;
    } catch (error) {
        console.log(error);
    }
};

const onDelete = async (id) => {
    try {
        await ElMessageBox.confirm('Delete Warning', 'Warning', {
            confirmButtonText: 'OK',
            cancelButtonText: 'Cancel',
            type: 'warning',
        });
        await axios.delete(`/settings/branch/${id}/delete`);
        fetchData();
        ElMessage({ type: 'success', message: 'Delete Success' });
    } catch (error) {
        console.error('Error deleting branch:', error);
        ElMessage({ type: 'error', message: 'Delete Cancel' });
    }
};

// Lifecycle hooks
onMounted(() => {
    fetchData();
});

</script>