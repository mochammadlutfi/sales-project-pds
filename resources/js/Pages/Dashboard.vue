
<template>
    <div class="flex justify-between items-center mb-6">
        <h4 class="text-slate-900 dark:text-slate-200 text-lg font-medium">Dashboard</h4>
    </div>
    <el-row :gutter="20">
        <el-col :span="8">
            <el-card v-loading="isLoading">
                <div class="flex items-center">
                    <div class="flex-shrink-0 me-3">
                        <div class="w-12 h-12 flex justify-center items-center rounded text-white bg-primary">
                            <i class="mgc_suitcase_line el-icon text-xl"></i>
                        </div>
                    </div>
                    <div class="flex-grow">
                        <h5 class="mb-1">Total Project</h5>
                        <p>{{ data.total_project }}</p>
                    </div>
                </div>
            </el-card>
        </el-col>
        <el-col :span="8">
            <el-card v-loading="isLoading">
                <div class="flex items-center">
                    <div class="flex-shrink-0 me-3">
                        <div class="w-12 h-12 flex justify-center items-center rounded text-white bg-primary">
                            <i class="mgc_suitcase_line el-icon text-xl"></i>
                        </div>
                    </div>
                    <div class="flex-grow">
                        <h5 class="mb-1">Project Ready</h5>
                        <p class="font-semibold">{{ data.project_ready }}</p>
                    </div>
                </div>
            </el-card>
        </el-col>
        <el-col :span="8">
            <el-card v-loading="isLoading">
                <div class="flex items-center">
                    <div class="flex-shrink-0 me-3">
                        <div class="w-12 h-12 flex justify-center items-center rounded text-white bg-primary">
                            <i class="mgc_suitcase_line el-icon text-xl"></i>
                        </div>
                    </div>
                    <div class="flex-grow">
                        <h5 class="mb-1">Total Aktivitas</h5>
                        <p>{{ data.total_activity }}</p>
                    </div>
                </div>
            </el-card>
        </el-col>
    </el-row>
</template>
<script setup>
import { useGeneralHelper } from '@/Composable/common';
import { ref, onMounted } from 'vue';

const { dateFormat } = useGeneralHelper();

const data = ref({
    project_ready : 0,
    total_project : 0,
    total_activity : 0,
});

const isLoading = ref(false);

const fetchData = async () => {
    try {
        isLoading.value = true;

        const response = await axios.get(`/dashboard`);
        if (response.status === 200) {
            data.value = response.data;
        }
    } catch (error) {
        console.error(error);
    } finally {
        isLoading.value = false;
    }
};

onMounted(() => {
    fetchData();
});
</script>
