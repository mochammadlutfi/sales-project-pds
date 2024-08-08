
<template>
    <div class="flex justify-between items-center mb-6">
        <h4 class="text-slate-900 dark:text-slate-200 text-lg font-medium">Dashboard</h4>
    </div>
    <el-row :gutter="20">
        <el-col :span="8">
            <el-card>
                <div class="flex items-center">
                    <div class="flex-shrink-0 me-3">
                        <div class="w-12 h-12 flex justify-center items-center rounded text-white bg-primary">
                            <i class="mgc_suitcase_line el-icon text-xl"></i>
                        </div>
                    </div>
                    <div class="flex-grow">
                        <h5 class="mb-1">Total Project</h5>
                        <p>85</p>
                    </div>
                </div>
            </el-card>
        </el-col>
        <el-col :span="8">
            <el-card>
                <div class="flex items-center">
                    <div class="flex-shrink-0 me-3">
                        <div class="w-12 h-12 flex justify-center items-center rounded text-white bg-primary">
                            <i class="mgc_suitcase_line el-icon text-xl"></i>
                        </div>
                    </div>
                    <div class="flex-grow">
                        <h5 class="mb-1">Project Ready</h5>
                        <p class="font-semibold">85</p>
                    </div>
                </div>
            </el-card>
        </el-col>
        <el-col :span="8">
            <el-card>
                <div class="flex items-center">
                    <div class="flex-shrink-0 me-3">
                        <div class="w-12 h-12 flex justify-center items-center rounded text-white bg-primary">
                            <i class="mgc_suitcase_line el-icon text-xl"></i>
                        </div>
                    </div>
                    <div class="flex-grow">
                        <h5 class="mb-1">Total Activity</h5>
                        <p>85</p>
                    </div>
                </div>
            </el-card>
        </el-col>
    </el-row>
</template>
<script setup>
import { useGeneralHelper } from '@/Composable/common';
const { dateFormat } = useGeneralHelper();
</script>
