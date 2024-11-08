<template>
    <div class="flex justify-between items-center mb-6">
        <h4 class="text-slate-900 dark:text-slate-200 text-lg font-medium">
            Project Detail
        </h4>

        <div class="md:flex hidden items-center gap-2.5 text-sm font-semibold">
            <el-breadcrumb separator="/">
                <el-breadcrumb-item :to="{ path: '/' }">Dashboard</el-breadcrumb-item>
                <el-breadcrumb-item :to="{ path: '/project' }">{{ $t('project.title')}}</el-breadcrumb-item>
                <el-breadcrumb-item>{{ data.name }}</el-breadcrumb-item>
            </el-breadcrumb>
        </div>
    </div>
    <el-card class="rounded-md mb-4" body-class="p-3" v-loading="isLoading">
        <div class="p-4">
            <div class="flex justify-between items-center">
                <div class="mb-4">
                    <div class="text-xs mb-1">Project Name</div>
                    <h3 class="text-lg font-bold">{{ data.name }}</h3>
                </div>
                <el-button type="primary" @click.prevent="updateStatus('done')" v-if="data.status != 'done'  && user != null && user.role != 'Operator'">
                    <i class="me-2 mgc_check_fill"></i>
                    Done
                </el-button>
            </div>
            <el-row :gutter="20">
                <el-col :span="12">
                    <el-row class="mb-2">
                        <el-col :span="8">Status</el-col>
                        <el-col :span="16" class="font-semibold">
                            : {{ data.status }}
                        </el-col>
                    </el-row>
                    <el-row class="mb-2">
                        <el-col :span="8">{{ $t('project.address')}}</el-col>
                        <el-col :span="16" class="font-semibold">
                            : {{ data.address ?? '-' }}
                        </el-col>
                    </el-row>
                    <el-row class="mb-2">
                        <el-col :span="8">{{ $t('project.customer_name')}}</el-col>
                        <el-col :span="16" class="font-semibold">
                            : {{ data.customer_name ?? '-' }}
                        </el-col>
                    </el-row>
                    <el-row class="mb-2">
                        <el-col :span="8">{{ $t('project.customer_phone')}}</el-col>
                        <el-col :span="16" class="font-semibold">
                            : {{ data.customer_phone ?? '-' }}
                        </el-col>
                    </el-row>
                    <el-row class="mb-2">
                        <el-col :span="8">{{ $t('project.customer_email')}}</el-col>
                        <el-col :span="16" class="font-semibold">
                            : {{ data.customer_email ?? '-' }}
                        </el-col>
                    </el-row>
                    <el-row class="mb-2" v-if="data.status == 'done'">
                        <el-col :span="8">{{ $t('project.description')}}</el-col>
                        <el-col :span="16" class="font-semibold">
                            : {{ data.note ?? '-' }}
                        </el-col>
                    </el-row>
                </el-col>
                <el-col :span="12">
                    <el-row class="mb-2">
                        <el-col :span="8">{{ $t('project.cp_name')}}</el-col>
                        <el-col :span="16" class="font-semibold">
                            : {{ data.cp_name ?? '-' }}
                        </el-col>
                    </el-row>
                    <el-row class="mb-2">
                        <el-col :span="8">{{ $t('project.cp_position')}}</el-col>
                        <el-col :span="16" class="font-semibold">
                            : {{ data.cp_position ?? '-' }}
                        </el-col>
                    </el-row>
                    <el-row class="mb-2">
                        <el-col :span="8">{{ $t('project.cp_phone')}}</el-col>
                        <el-col :span="16" class="font-semibold">
                            : {{ data.cp_phone ?? '-' }}
                        </el-col>
                    </el-row>
                    <el-row class="mb-2">
                        <el-col :span="8">{{ $t('branch')}}</el-col>
                        <el-col :span="16" class="font-semibold">
                            : {{ data.branch ? data.branch.name : '-' }}
                        </el-col>
                    </el-row>
                    <el-row class="mb-2">
                        <el-col :span="8">{{ $t('sales_person')}}</el-col>
                        <el-col :span="16" class="font-semibold">
                            : {{ data.sales ? data.sales.name : '-' }}
                        </el-col>
                    </el-row>
                    <el-row class="mb-2">
                        <el-col :span="8">{{ $t('project.amount')}}</el-col>
                        <el-col :span="16" class="font-semibold">
                            : {{ data.amount ?? '-' }}
                        </el-col>
                    </el-row>
                </el-col>
            </el-row>
        </div>
    </el-card>
    
    <div class="flex justify-between items-center mb-6">
        <h4 class="text-slate-900 dark:text-slate-200 text-lg font-medium">
            {{ $t('project.activity') }}
        </h4>
    </div>

    <el-card body-class="p-0" v-loading="activityLoading">
        <div class="flex justify-between items-center p-4">
            <el-select v-model="params.limit" placeholder="Pilih" style="width: 115px" @change="fetchData(1)">
                <el-option label="25" value="25"/>
                <el-option label="50" value="50"/>
                <el-option label="100" value="100"/>
            </el-select>

            <div class="flex items-center gap-2">
                <el-button type="primary" @click.prevent="doExport" v-if="user != null && user.role != 'Operator'">
                    <i class="mgc_download_line me-2"></i>
                    Export Excel
                </el-button>
                <el-button type="primary" @click.prevent="onCreate()">
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
                            <!-- <el-form-item :label="$t('name')">
                                <el-input v-model="params.name" />
                            </el-form-item> -->
                        </el-col>
                        <el-col :md="8">
                        </el-col>
                    </el-row>
                    
                    <div class="flex items-center gap-2">
                        <el-button type="primary" native-type="submit">
                            <i class="mgc_search_line me-2"></i>
                            {{ $t("search") }}
                        </el-button>
                        <el-button type="primary" @click.prevent="onResetFilter">
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
                    
                    <!-- <el-image
                    class="h-12"
                    :src="scope.row.image_url"
                    :zoom-rate="1.2"
                    :max-scale="7"
                    :min-scale="0.2"
                    :preview-src-list="srcList"
                    :initial-index="4"
                    fit="cover"
                    /> -->
                </template>
            </el-table-column>
            <el-table-column :label="$t('project.description')">
                <template #default="scope">
                    {{ scope.row.description }}
                </template>
            </el-table-column>
            <el-table-column :label="$t('action')" align="center" width="100">
                <template #default="scope">
                    <el-dropdown popper-class="dropdown-action" trigger="click" >
                        <el-button type="primary">
                            {{ $t('action') }}
                        </el-button>
                        <template #dropdown>
                            <el-dropdown-menu>
                                <el-dropdown-item @click.prevent="onEdit(scope.row)">
                                    <i class="mgc_edit_line"></i>
                                    {{  $t('edit') }}
                                </el-dropdown-item>
                                <el-dropdown-item @click.prevent="onDelete(scope.row.id)">
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
            <el-form-item :label="$t('project.date')" :error="errors.date">
                <el-date-picker
                    v-model="form.date"
                    type="date"/>
            </el-form-item>
            <el-form-item :label="$t('project.description')">
                <el-input type="textarea" v-model="form.description" />
            </el-form-item>
            <upload-image v-model="form.image" width="140px" height="140px" 
            class="mb-5"
            autoCrop
            />
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
    <el-dialog
    v-model="modalNote"
    title="Konfirmasi Project Selesai?"
    class="rounded-md" v-loading="modalNoteLoading">
        <el-form :model="form" @submit.prevent="onUpdateStatus" label-position="top">
            <el-form-item :label="$t('project.description')">
                <el-input type="textarea" v-model="note" />
            </el-form-item>
            <div class="text-right">
                <el-button type="danger" plain @click.prevent="modalNote = false">
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
import { ref, onMounted, computed } from 'vue';
import { useRoute } from 'vue-router';
import { useI18n } from 'vue-i18n';
import UploadImage from '@/Components/Form/UploadImage.vue';
import { useGeneralHelper } from '@/Composable/common.js';
import { useAuthStore } from '@/stores/auth';

const user = computed(() => useAuthStore().user);
const helper = useGeneralHelper();
const { t } = useI18n();
const route = useRoute();
const data = ref({});
const id = ref(route.params.id);
const isLoading = ref(false);
const activityLoading = ref(false);
const dataList = ref([]);
const params = ref({
    page: 1,
    limit: 25,
    from: 0,
    to: 0,
    total: 0,
    pageSize: 0,
    name: null,
    project_id: route.params.id,
});
const filterShow = ref(false);

const modalLoading = ref(false);
const modalForm = ref(false);
const modalTitle = ref('');
const form = ref({
    id: null,
    project_id : route.params.id,
    image :null,
    date : null,
    description : null
});
const errors = ref({});
const modalNote = ref(false);
const modalNoteLoading = ref(false);
const note = ref('');
const doFilter = () => {
    filterShow.value = !filterShow.value;
};

const onResetFilter = () => {

}

const updateStatus = (status) => {
    if(status == 'done'){
        modalNote.value = true;
    }
}

const onUpdateStatus = async () => {
    modalNoteLoading.value = true;
    const url = `/project/${data.value.id}/done`;
    try {
        const response = await axios.post(url, {
            note : note.value
        }, {
            headers: {
                'Content-Type': 'multipart/form-data'
            }
        });
        ElMessage({ message: 'Success Message', type: 'success' });
        modalNote.value = false;
        fetchData();
        fetchActivity();
    } catch (error) {
        errors.value = error.validation;
        ElMessage({ message: 'Error Message', type: 'error' });
    } finally {
        modalNoteLoading.value = false;
    }
};
const fetchData = async () => {
    try {
        isLoading.value = true;

        const response = await axios.get(`/project/${route.params.id}`);
        if (response.status === 200) {
            data.value = response.data;
        }
    } catch (error) {
        console.error(error);
    } finally {
        isLoading.value = false;
    }
};

const onCreate = () => {
    modalForm.value = true;
    modalTitle.value = `${t('create')} ${t('project.activity')}`;
};
const onReset = () => {
    form.id = null;
    form.date = null;
    form.description = null;
    form.image = null;
}

const onSubmit = async () => {
    modalLoading.value = true;
    const url = form.value.id ? `/activity/${form.value.id}/update` : '/activity/store';
    try {
        const response = await axios.post(url, form.value, {
            headers: {
                'Content-Type': 'multipart/form-data'
            }
        });
        ElMessage({ message: 'Success Message', type: 'success' });
        modalForm.value = false;
        onReset();
        fetchActivity();
    } catch (error) {
        console.log(error);
        errors.value = error.validation;
        ElMessage({ message: 'Error Message', type: 'error' });
    } finally {
        modalLoading.value = false;
    }
};

const doExport = () => {
    window.location.href  = '/report/project/' + data.value.id;
}

const fetchActivity = async (page = 1) => {
    try {
        activityLoading.value = true;
        const response = await axios.get('/activity', { params : params.value });
        if (response.status === 200) {
            dataList.value = response.data.data;
            params.value.from = response.data.from;
            params.value.to = response.data.to;
            params.value.page = response.data.current_page;
            params.value.total = response.data.total;
            params.value.pageSize = response.data.per_page;
        }
    } catch (error) {
        console.error(error);
    }finally {
        activityLoading.value = false;
    }
};

const onEdit = (data) => {
    modalForm.value = true;
    modalTitle.value = `${t('edit')} ${'project.activity'}`;
    form.value.id = data.id;
    form.value.date = data.date;
    form.value.description = data.description;
    form.value.image = data.image;
};

const onDelete = async (id) => {
    try {
        await ElMessageBox.confirm(t('delete_confirm'), t('warning'), {
            confirmButtonText: 'OK',
            cancelButtonText: 'Cancel',
            type: 'warning',
        });
        await axios.delete(`/activity/${id}/delete`);
        fetchActivity();
        ElMessage({ type: 'success', message: 'Delete Success' });
    } catch (error) {
        console.error('Error deleting activity:', error);
        ElMessage({ type: 'error', message: 'Delete Cancel' });
    }
};
onMounted(() => {
    fetchData();
    fetchActivity();
});


</script>