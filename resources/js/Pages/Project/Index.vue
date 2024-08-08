<template>    
    <div class="flex justify-between items-center mb-6">
        <h4 class="text-slate-900 dark:text-slate-200 text-lg font-medium">
            {{ $t('project.title') }}
        </h4>

        <div class="md:flex hidden items-center gap-2.5 text-sm font-semibold">
            <el-breadcrumb separator="/">
                <el-breadcrumb-item :to="{ path: '/' }">Dashboard</el-breadcrumb-item>
                <el-breadcrumb-item>{{ $t('project.title') }}</el-breadcrumb-item>
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
                <el-button type="primary" @click.prevent="openExport">
                    <i class="mgc_download_line me-2"></i>
                    {{ 'Excel Report' }}
                </el-button>
                <el-button type="primary" tag="router-link"  to="/project/create">
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
                        <el-col :md="8">
                            <el-form-item :label="$t('sales_person')">
                                <select-sales v-model="params.sales_id"/>
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
        <el-table class="min-w-full" border
            :data="dataList" v-loading="loading">
            <el-table-column prop="name" :label="$t('name')" width="300"/>
            <el-table-column :label="$t('project.customer')">
                <template #default="scope">
                    <div>
                        Name : {{ scope.row.customer_name }}
                    </div>
                    <div>
                        Phone : {{ scope.row.customer_phone }}
                    </div>
                </template>
            </el-table-column>
            <el-table-column :label="$t('project.cp')">
                <template #default="scope">
                    <div>
                        Name : {{ scope.row.cp_name }}
                    </div>
                    <div>
                        Phone : {{ scope.row.cp_phone }}
                    </div>
                </template>
            </el-table-column>
            <el-table-column :label="$t('sales_person')">
                <template #default="scope">
                    {{ scope.row.sales.name }}
                </template>
            </el-table-column>
            <el-table-column prop="branch.name" :label="$t('branch')" width="140 "/>
            <el-table-column :label="$t('action')" align="center" width="100">
                <template #default="scope">
                    <el-dropdown popper-class="dropdown-action" trigger="click" >
                        <el-button type="primary">
                            {{ $t('action') }}
                        </el-button>
                        <template #dropdown>
                            <el-dropdown-menu>
                                <el-dropdown-item @click.prevent="onEdit(scope.row.id)">
                                    <router-link :to="{ name: 'project.show', params: { id: scope.row.id } }">
                                        <i class="mgc_eye_line"></i>
                                        {{  $t('detail') }}
                                    </router-link>
                                </el-dropdown-item>
                                <el-dropdown-item @click.prevent="onEdit(scope.row.id)">
                                    <router-link :to="{ name: 'project.edit', params: { id: scope.row.id } }">
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
    <el-dialog v-model="printDialog" title="Excel Report" width="500" v-loading="printLoading">
        <el-form label-position="top" @submit.prevent="doDownload">
            <el-form-item :label="$t('name')">
                <el-input v-model="params.name" />
            </el-form-item>
            <el-form-item :label="$t('branch')">
                <select-branch v-model="report.branch_id" placeholder="Semua Cabang"/>
            </el-form-item>
            <el-form-item :label="$t('sales_person')">
                <select-sales v-model="report.sales_id" placeholder="Semua Sales"/>
            </el-form-item>
            <div class="flex justify-end">
                <el-button @click="printDialog = false">Batal</el-button>
                <el-button type="primary" native-type="submit">
                    Download
                </el-button>
            </div>
        </el-form>
    </el-dialog>
</template>
<script setup>
import SelectBranch from "@/Components/Form/SelectBranch.vue";
import SelectSales from "@/Components/Form/SelectSales.vue";
import { ref, reactive, onMounted } from 'vue';
import axios from 'axios';
import { ElMessage, ElMessageBox } from 'element-plus';
import fs from 'fs/promises';

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
    sales_id: null
});
const filterShow = ref(false);
const printDialog = ref(false);
const printLoading = ref(false);
const resetFilter = () => {
    params.value.name = null;
    params.value.branch_id = null;
    params.value.sales_id = null;
    fetchData(1);
};
const report = ref({
    status : null,
    sales_id : null,
    branch_id : null
});
const fetchData = async (page = 1) => {
    try {
        loading.value = true;
        const response = await axios.get('/project', { params : params.value });
        if (response.status === 200) {
            dataList.value = response.data.data;
            Object.assign(params, {
                from: response.data.from,
                to: response.data.to,
                page: response.data.current_page,
                total: response.data.total,
                pageSize: response.data.per_page,
            });
        }
        loading.value = false;
    } catch (error) {
        console.error(error);
    }
};

const openExport = () => {
    printDialog.value = true;
}

const doDownload = async () => {
    // try {
    //     printLoading.value = true;
    //     const response = await axios.get('/project/export', {responseType: 'stream', params : report.value });
    //     if (response.status === 200) {
    //         response.data.pipe(fs.createWriteStream("/temp/easy.xlsx"));
    //     }
    //     printLoading.value = false;
    // } catch (error) {
    //     console.error(error);
    // }
    window.location.href  = '/report/project';
}


onMounted(() => {
    fetchData();
});
</script>