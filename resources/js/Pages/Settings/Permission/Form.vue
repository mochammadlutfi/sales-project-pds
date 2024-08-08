<template>    
    <div class="flex justify-between items-center mb-6">
        <h4 class="text-slate-900 dark:text-slate-200 text-lg font-medium">
            {{ $t("create") }} {{ $t('role_permission') }}
        </h4>

        <div class="md:flex hidden items-center gap-2.5 text-sm font-semibold">
            <el-breadcrumb separator="/">
                <el-breadcrumb-item :to="{ path: '/' }">Settings</el-breadcrumb-item>
                <el-breadcrumb-item>User</el-breadcrumb-item>
            </el-breadcrumb>
        </div>
    </div>
    <el-card body-class="p-0">
        <el-form :model="form" @submit.prevent="onSubmit" label-position="top">
            <div class="p-4">
                <el-form-item :label="$t('role')">
                    <el-input v-model="form.name" />
                </el-form-item>
            </div>

            <el-table :data="permissionList" >
                <el-table-column :label="$t('name')">
                    <template #default="scope">
                        {{ $t(scope.row.name) }}
                    </template>
                </el-table-column>

                <el-table-column :label="$t('view')" width="100">
                    <template #default="scope">
                        <el-checkbox v-model="scope.row.view"/>    
                    </template>
                </el-table-column>
                <el-table-column :label="$t('create')" width="100">
                    <template #default="scope">
                        <el-checkbox v-model="scope.row.create"/>    
                    </template>
                </el-table-column>
                <el-table-column :label="$t('edit')" width="100">
                    <template #default="scope">
                        <el-checkbox v-model="scope.row.update"/>    
                    </template>
                </el-table-column>
                <el-table-column :label="$t('delete')" width="100">
                    <template #default="scope">
                        <el-checkbox v-model="scope.row.delete"/>    
                    </template>
                </el-table-column>
            </el-table>

            <div class="p-4 ">
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
<script>
export default {
    data() {
        return {
            form : {
                id : this.$route.params.id,
                name : null,
            },
            loadingForm : false,
            permissionList : [],
        }    
    },
    created(){
        this.fetchPermission();
    },
    mounted(){
        if(this.$route.params.id){
            this.fetchData(this.$route.params.id);
        }
    },
    methods : {
        async fetchPermission(){
            try {
                const response = await axios.get('/settings/permissions/list');
                if(response.status == 200){
                    this.permissionList = response.data;
                }
            } catch (error) {
                console.error(error);
            }
        },
        async fetchData(id){
            try {
                const response = await axios.get('/settings/permissions/'+ id);
                if(response.status == 200){
                    const data = response.data;
                    this.form.id = data.id;
                    this.form.name = data.name;
                    data.permissions.forEach(item2 => {
                        let [name, action] = item2.name.split('.');

                        let item1 = this.permissionList.find(item1 => item1.name === name);
                        if (item1) {
                            item1[action] = true;
                        }
                        console.log(this.permissionList.find(item1 => item1.name === name));
                    });
                    this.$forceUpdate();

                }
            } catch (error) {
                console.error(error);
            }
        },
        async onSubmit()
        {
            this.loadingForm = true;

            const form = Object.assign(this.form, {
                    lines : this.permissionList,
                }
            );
            var url = (this.form.id) ? `/settings/permissions/${this.form.id}/update` : '/settings/permissions/store';
            await axios.post(url, form).then(response => {
                const data = response.data.result;
                ElMessage({
                    message: this.$t('success_msg'),
                    type: 'success',
                });
                this.$router.push({name : 'permissions'});
            }).catch(error => {
                console.log(error);
                this.errors = error.validation;
                ElMessage({
                    message: this.$t('error_msg'),
                    type: 'error',
                });
            });
            this.loadingForm = false;
        },
    }
}
</script>
