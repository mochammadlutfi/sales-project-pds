<template>
    <div class="flex justify-between items-center mb-6">
        <h4 class="text-slate-900 dark:text-slate-200 text-lg font-medium">
            {{ $t(title) }}
        </h4>

        <div class="md:flex hidden items-center gap-2.5 text-sm font-semibold">
            <el-breadcrumb separator="/">
                <el-breadcrumb-item :to="{ path: '/' }">Profile</el-breadcrumb-item>
                <el-breadcrumb-item>Edit</el-breadcrumb-item>
            </el-breadcrumb>
        </div>
    </div>
    <el-row :gutter="20">
        <el-col :lg="6">
            <el-card class="shadow-sm sm:rounded-lg" body-class="p-2">
                <div class="space-y-2">
                    <router-link to="/profile" exactActiveClass="text-gray-500 bg-slate-100"
                    class="flex items-center py-2 px-4 text-sm rounded hover:text-slate-700 dark:text-gray-400 dark:bg-gray-700 dark:hover:text-gray-300">
                        <i class="me-3.5 mgc_user_3_line text-lg"></i>
                        <span>Profil</span>
                    </router-link>
                    <router-link to="/profile/password" 
                    class="flex items-center py-2 px-4 text-sm rounded hover:text-slate-700 dark:text-gray-400 dark:bg-gray-700 dark:hover:text-gray-300"
                    exactActiveClass="text-gray-500 bg-slate-100"
                    >
                        <i class="me-3.5 mgc_lock_line text-lg"></i>
                        <span>Password</span>
                    </router-link>
                    <router-link to="/profile/devices" 
                    class="flex items-center py-2 px-4 text-sm rounded hover:text-slate-700 dark:text-gray-400 dark:bg-gray-700 dark:hover:text-gray-300"
                    exactActiveClass="text-gray-500 bg-slate-100"
                    >
                    <i class="me-3.5 mgc_device_line text-lg"></i>
                    <span>Devices</span>
                    </router-link>
                </div>
            </el-card>
        </el-col>
        <el-col :lg="18">
            <router-view @childinit="childInit"></router-view>
        </el-col>
    </el-row>
</template>

<script>
export default {
  data() {
    return {
      title: '',
    }
  },
  methods: {
    childInit(title) {
      this.title = title
    },
  },
}
</script>
