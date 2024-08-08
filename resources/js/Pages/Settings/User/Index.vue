<template>    
    <div class="flex justify-between items-center mb-6">
        <h4 class="text-slate-900 dark:text-slate-200 text-lg font-medium">User</h4>

        <div class="md:flex hidden items-center gap-2.5 text-sm font-semibold">
            <el-breadcrumb separator="/">
                <el-breadcrumb-item :to="{ path: '/' }">Dashboard</el-breadcrumb-item>
                <el-breadcrumb-item :to="{ path: '/settings' }">{{ $t('settings')}}</el-breadcrumb-item>
                <el-breadcrumb-item>User</el-breadcrumb-item>
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
                <el-button type="primary" tag="router-link"  to="/settings/user/create">
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
                            <el-form-item :label="$t('username')">
                                <el-input v-model="params.username" />
                            </el-form-item>
                        </el-col>
                        <el-col :md="8">
                            <el-form-item :label="$t('email')">
                                <el-input v-model="params.email" />
                            </el-form-item>
                        </el-col>
                        <el-col :md="8">
                            <el-form-item :label="$t('role')">
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
            <el-table-column prop="username" :label="$t('username')"/>
            <el-table-column prop="email" :label="$t('email')"/>
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
                                    <router-link :to="{ name: 'user.edit', params: { id: scope.row.id } }">
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
<script>
import SelectBranch from "@/Components/Form/SelectBranch.vue";
export default {
    components: {
        SelectBranch
    },
    data(){
        return {
            loading : false,
            dataList : [],
            params : {
                page : 1,
                limit : 25,
                from : 0,
                to : 0,
                total  : 0,
                pageSize : 0,
                name : "",
                username : null,
                email : null,
                role_id : null,
                branch_id : null,
            },
            filterShow : false,
            modalForm : false,
            modalTitle : this.$t('create') + ' ' + this.$t('user'),
            form : {
                id : null,
                name : null,
                email : null,
                username : null,
                role_id : null,
                password : null,
                confirmation_password : null,
            }
        }
    },
    async mounted(){
        await this.fetchData()
    },
    methods : {
        resetFilter()
        {
            this.params.name = null;
            this.params.username = null;
            this.params.email = null;
            this.params.role_id = null;
            this.params.branch_id = null;
            this.fetchData(1);
        },
        async fetchData(page) {
            var page = (page == undefined) ? 1 : page;
            try {
                this.loading = true;
                const response = await axios.get('/settings/user',{
                    params: this.params
                });
                if(response.status == 200){
                    this.dataList = response.data.data;
                    this.params.from = response.data.from;
                    this.params.to = response.data.to;
                    this.params.page = response.data.current_page;
                    this.params.total = response.data.total;
                    this.params.pageSize = response.data.per_page;
                }
                this.loading = false;
            } catch (error) {
                console.error(error);
            }
        },
        async onSubmit()
        {
            this.loadingForm = true;
            var url = (this.form.id) ? `/settings/user/${this.form.id}/update` : '/settings/user/store';
            await axios.post(url, this.form).then(response => {
                const data = response.data.result;
                console.log(data);
                ElMessage({
                    message: this.$t('success_msg'),
                    type: 'success',
                });
                this.modalForm = false;
                this.resetForm();
                this.fetchData();
            }).catch(error => {
                this.errors = error.validation;
                ElMessage({
                    message: this.$t('error_msg'),
                    type: 'error',
                });
            });
            this.loadingForm = false;
        },
        create(){
            this.modalForm = true;
        },
        resetForm(){
            this.form.id = null;
            this.form.name = null;
            this.form.manager_id = null;
            this.form.address = null;
        },
        async onEdit(id){
            await axios.get(`/settings/user/${id}`).then(response => {
                const data = response.data;
                console.log(data);
                this.form.id = data.id;
                this.form.name = data.name;
                this.form.manager_id = data.manager_id;
                this.form.address = data.address;
                this.modalTitle = this.$t('edit') +' '+this.$t('branch');
                this.modalForm = true;
            }).catch(error => {
                console.log(error);
            });
        },
        onDelete(id){
            ElMessageBox.confirm(this.$t("delete_warning"), this.$t('warning'), {
                confirmButtonText: this.$t("ok"),
                cancelButtonText: this.$t("cancel"),
                type: 'warning',
            }).then(() => {
                // User confirmed deletion
                axios.delete(`/settings/user/${id}/delete`).then(() => {
                    // Deletion successful
                    this.fetchData();
                    ElMessage({
                        type: 'success',
                        message: this.$t('delete_success'),
                    });
                }).catch(error => {
                    // Error occurred during deletion
                    console.error('Error deleting branch:', error);
                    ElMessage({
                        type: 'error',
                        message: this.$t('delete_cancel'),
                    });
                });
            }).catch(() => {
                // User cancelled deletion
                ElMessage({
                    type: 'info',
                    message: this.$t('delete_cancel')
                });
            });
        },
    }
}
</script>