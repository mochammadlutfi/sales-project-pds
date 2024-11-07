<template>    
    <div class="flex justify-between items-center mb-6">
        <h4 class="text-slate-900 dark:text-slate-200 text-lg font-medium">
            Sales Person
        </h4>

        <div class="md:flex hidden items-center gap-2.5 text-sm font-semibold">
            <el-breadcrumb separator="/">
                <el-breadcrumb-item :to="{ path: '/' }">Dashboard</el-breadcrumb-item>
                <el-breadcrumb-item>Sales Person</el-breadcrumb-item>
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
                <el-button type="primary" tag="router-link"  to="/sales/create">
                    <i class="mgc_add_line me-2"></i>
                    {{ $t('create') }}
                </el-button>
            </div>
        </div>
        
        <transition name="slide-down">
            <div class="card-filter p-4" v-if="filterShow">
                <el-form label-position="top" @submit.prevent="fetchData">
                    <el-row :gutter="20">
                        <el-col :md="8">
                            <el-form-item :label="$t('name')">
                                <el-input v-model="params.name" />
                            </el-form-item>
                        </el-col>
                        <el-col :md="8">
                            <el-form-item :label="$t('branch')">
                                <select-branch v-model="params.branch_id"/>
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
            <el-table-column prop="name" :label="$t('name')"/>
            <el-table-column prop="email" :label="$t('email')"/>
            <el-table-column prop="phone" :label="$t('phone')"/>
            <el-table-column prop="branch.name" :label="$t('branch')"/>
            <el-table-column :label="$t('action')" align="center" width="100">
                <template #default="scope">
                    <el-dropdown popper-class="dropdown-action" trigger="click" >
                        <el-button type="primary">
                            {{ $t('action') }}
                        </el-button>
                        <template #dropdown>
                            <el-dropdown-menu>
                                <el-dropdown-item @click.prevent="onEdit(scope.row.id)">
                                    <router-link :to="{ name: 'sales.edit', params: { id: scope.row.id } }">
                                        <i class="mgc_edit_line"></i>
                                        {{  $t('edit') }}
                                    </router-link>
                                </el-dropdown-item>
                                <el-dropdown-item @click.prevent="onDelete(scope.row.id)" v-if="scope.row.id != 1">
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
</template>
<script setup>
import SelectBranch from "@/Components/Form/SelectBranch.vue";
import { ref, onMounted } from 'vue';
import axios from 'axios';
import { ElMessage, ElMessageBox } from 'element-plus';

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
    branch_id: null,
});
const filterShow = ref(false);

const resetFilter = () => {
    params.value.name = null;
    params.value.branch_id = null;
    fetchData();
};

const fetchData = async (page = 1) => {
    try {
        loading.value = true;
        const response = await axios.get('/sales',  { params : params.value });
        if (response.status === 200) {
            dataList.value = response.data.data;
            dataList.value = response.data.data;
            params.value.from = response.data.from;
            params.value.to = response.data.to;
            params.value.page = response.data.page;
            params.value.total = response.data.total;
            params.value.pageSize = response.data.pageSize;
        }
        loading.value = false;
    } catch (error) {
        console.error(error);
    }
};

const onDelete  = async (id) => {
    try {
        await ElMessageBox.confirm('Delete Warning', 'Warning', {
            confirmButtonText: 'OK',
            cancelButtonText: 'Cancel',
            type: 'warning',
        });
        await axios.delete(`/sales/${id}/delete`);
        fetchData();
        ElMessage({ type: 'success', message: 'Delete Success' });
    } catch (error) {
        console.error('Error deleting branch:', error);
        ElMessage({ type: 'error', message: 'Delete Cancel' });
    }
};

onMounted(() => {
    fetchData();
});
</script>