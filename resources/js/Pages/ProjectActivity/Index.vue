<template>    
    <div class="flex justify-between items-center mb-6">
        <h4 class="text-slate-900 dark:text-slate-200 text-lg font-medium">
            {{ $t('project.activity') }}
        </h4>

        <div class="md:flex hidden items-center gap-2.5 text-sm font-semibold">
            <el-breadcrumb separator="/">
                <el-breadcrumb-item :to="{ path: '/' }">Dashboard</el-breadcrumb-item>
                <el-breadcrumb-item>{{ $t('project.title') }}</el-breadcrumb-item>
            </el-breadcrumb>
        </div>
    </div>
    
    <el-card body-class="p-0" v-loading="loading">
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
                            <el-form-item :label="$t('sales_person        ')">
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
            :data="dataList">
            <el-table-column :label="$t('project.date')" width="150px">
                <template #default="scope">
                    {{ helper.dateFormat(scope.row.date) }}
                </template>
            </el-table-column>
            <el-table-column :label="$t('project.image')" width="100px">
                <template #default="scope">
                    <img :src="scope.row.image_url" class="h-12"/>
                </template>
            </el-table-column>
            <el-table-column :label="$t('project.description')">
                <template #default="scope">
                    {{ scope.row.description }}
                </template>
            </el-table-column>
            <el-table-column :label="$t('project.title')">
                <template #default="scope">
                    {{ scope.row.project.name }}
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
import { ref, reactive, onMounted } from 'vue';
import axios from 'axios';
import { useGeneralHelper } from '@/Composable/common.js';

const helper = useGeneralHelper();

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
    address: null,
});
const filterShow = ref(false);

const resetFilter = () => {
    params.value.name = null;
    params.value.address = null;
    fetchData(1);
};

const fetchData = async (page = 1) => {
    try {
        loading.value = true;
        const response = await axios.get('/activity', { params : params.value });
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


onMounted(() => {
    fetchData();
});
</script>