<template>    
    <div class="flex justify-between items-center mb-6">
        <h4 class="text-slate-900 dark:text-slate-200 text-lg font-medium">
            {{ $t('role_permission') }}
        </h4>

        <div class="md:flex hidden items-center gap-2.5 text-sm font-semibold">
            <el-breadcrumb separator="/">
                <el-breadcrumb-item :to="{ path: '/' }">Settings</el-breadcrumb-item>
                <el-breadcrumb-item>User</el-breadcrumb-item>
            </el-breadcrumb>
        </div>
    </div>
    <el-card body-class="p-0">
        <div class="flex justify-between items-center p-4">
            <el-input
            v-model="params.search"
            class="w-1/3"
            placeholder="Search"
            clearable
            >
            <template #prefix>
                <i class="mgc_search_line"></i>
            </template>
            </el-input>

            <div class="flex items-center gap-2">
                <router-link to="/settings/permissions/create" class="el-button el-button--primary">
                    <i class="mgc_add_line me-2"></i>
                    {{ $t('create') }}
                </router-link>
            </div>
        </div>
        <el-table
            :data="dataList" 
            v-loading="loading"
            >
            <el-table-column prop="name" :label="$t('name')"/>
            <el-table-column prop="users_count" :label="$t('total_user')"/>
            <el-table-column label="Aksi" align="center" width="100">
                <template #default="scope">
                    <el-dropdown popper-class="dropdown-action" trigger="click">
                        <el-button type="primary">
                        Aksi <i class="fa fa-angle-down ms-1"></i>
                        </el-button>
                        <template #dropdown>
                            <el-dropdown-menu>
                                <el-dropdown-item>
                                    <router-link :to="{ name: 'permissions.edit', params: { id: scope.row.id } }">
                                    <i class="mgc_edit_line"></i>
                                        Ubah
                                    </router-link>
                                </el-dropdown-item>
                                <el-dropdown-item @click.prevent="hapus(scope.row.id)">
                                    <i class="mgc_delete_2_line"></i>
                                    Hapus
                                </el-dropdown-item>
                            </el-dropdown-menu>
                        </template>
                    </el-dropdown>
                </template>
            </el-table-column>
        </el-table>
        <div class="flex justify-between items-center p-4">
            <div class="flex items-center gap-2">
                <p class="mb-0">Menampilkan {{ params.from }} sampai {{ params.to }} dari {{ params.total }}</p>
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
export default {
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
                search : "",
            }
        }
    },
    async mounted(){
        await this.fetchData()
    },
    methods : {
        async fetchData(page) {
            var page = (page == undefined) ? 1 : page;
            try {
                this.loading = true;
                const response = await axios.get('/settings/permissions',{
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
    }
}
</script>