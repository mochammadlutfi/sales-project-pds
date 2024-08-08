<template>
    <div class="flex justify-between items-center mb-6">
        <h4 class="text-slate-900 dark:text-slate-200 text-lg font-medium">
            {{ $t(title) }}
        </h4>

        <div class="md:flex hidden items-center gap-2.5 text-sm font-semibold">
            <el-breadcrumb separator="/">
                <el-breadcrumb-item :to="{ path: '/' }">Settings</el-breadcrumb-item>
                <el-breadcrumb-item>System</el-breadcrumb-item>
            </el-breadcrumb>
        </div>
    </div>
    <el-row :gutter="20">
        <el-col :lg="6">
            <el-card class="shadow-sm sm:rounded-lg" body-class="p-2">
                <div class="space-y-2">
                    <router-link to="/settings/system" exactActiveClass="text-gray-500 bg-slate-100"
                    class="flex items-center py-2 px-4 text-sm rounded hover:text-slate-700 dark:text-gray-400 dark:bg-gray-700 dark:hover:text-gray-300">
                        <svg xmlns="http://www.w3.org/2000/svg" class="me-3.5 w-5" width="24" height="24" viewBox="0 0 24 24"><g fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" color="currentColor"><path d="M15.5 12a3.5 3.5 0 1 1-7 0a3.5 3.5 0 0 1 7 0"/><path d="M20.79 9.152C21.598 10.542 22 11.237 22 12s-.403 1.458-1.21 2.848l-1.923 3.316c-.803 1.384-1.205 2.076-1.865 2.456s-1.462.38-3.065.38h-3.874c-1.603 0-2.405 0-3.065-.38s-1.062-1.072-1.865-2.456L3.21 14.848C2.403 13.458 2 12.763 2 12s.403-1.458 1.21-2.848l1.923-3.316C5.936 4.452 6.338 3.76 6.998 3.38S8.46 3 10.063 3h3.874c1.603 0 2.405 0 3.065.38s1.062 1.072 1.865 2.456z"/></g></svg>
                        <span>General</span>
                    </router-link>
                    <router-link to="/settings/system/email" 
                    class="flex items-center py-2 px-4 text-sm rounded hover:text-slate-700 dark:text-gray-400 dark:bg-gray-700 dark:hover:text-gray-300"
                    exactActiveClass="text-gray-500 bg-slate-100"
                    >
                    <svg xmlns="http://www.w3.org/2000/svg" class="me-3.5 w-5" width="24" height="24" viewBox="0 0 24 24"><g fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" color="currentColor"><path d="m2 5l6.913 3.925c2.526 1.433 3.648 1.433 6.174 0L22 5"/><path d="M11 19.49s-.93.024-1.9 0c-3.15-.08-4.724-.12-5.855-1.257s-1.164-2.672-1.23-5.742a69 69 0 0 1 0-2.956c.066-3.07.099-4.605 1.23-5.742c1.131-1.138 2.706-1.177 5.854-1.256c1.94-.05 3.862-.05 5.803 0c3.149.079 4.723.118 5.854 1.256s1.164 2.672 1.23 5.742c.007.357.012.977.014 1.478"/><path d="M18 20.214V21.5m0-1.286a3.36 3.36 0 0 1-2.774-1.43M18 20.213a3.36 3.36 0 0 0 2.774-1.43M18 13.785c1.157 0 2.176.568 2.774 1.43M18 13.787a3.36 3.36 0 0 0-2.774 1.43M18 13.787V12.5m4 1.929l-1.226.788M14 19.57l1.226-.788M14 14.43l1.226.788M22 19.57l-1.226-.788m0-3.566a3.12 3.12 0 0 1 0 3.566m-5.548-3.566a3.12 3.12 0 0 0 0 3.566"/></g></svg>
                        <span>Email</span>
                    </router-link>
                    <router-link to="/settings/system/language" 
                    class="flex items-center py-2 px-4 text-sm rounded hover:text-slate-700 dark:text-gray-400 dark:bg-gray-700 dark:hover:text-gray-300"
                    exactActiveClass="text-gray-500 bg-slate-100"
                    >
                    <svg xmlns="http://www.w3.org/2000/svg" class="me-3.5 w-5" width="24" height="24" viewBox="0 0 24 24"><g fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" color="currentColor"><path d="M5 5.966h3.15m3.85 0h-1.75m-2.1 0h2.1m-2.1 0V5m2.1.966c-.37 1.32-1.142 2.568-2.025 3.665M5.975 12c.713-.656 1.52-1.46 2.25-2.369m0 0c-.45-.528-1.08-1.381-1.26-1.767m1.26 1.767l1.35 1.403"/><path d="M7.022 16.978c.055 1.72.242 2.775.886 3.56a4 4 0 0 0 .554.554C9.57 22 11.212 22 14.5 22s4.931 0 6.038-.908q.304-.25.554-.554C22 19.43 22 17.788 22 14.5c0-3.287 0-4.931-.908-6.038a4 4 0 0 0-.554-.554c-.782-.641-1.831-.83-3.538-.885m-9.978 9.955c-1.72-.055-2.775-.242-3.56-.886a4 4 0 0 1-.554-.554C2 14.43 2 12.788 2 9.5c0-3.287 0-4.931.908-6.038a4 4 0 0 1 .554-.554C4.57 2 6.212 2 9.5 2c3.287 0 4.931 0 6.038.908a4 4 0 0 1 .554.554c.644.785.831 1.84.886 3.56l.022.001m-9.978 9.955L17 7.023"/><path d="m13 19l.833-2M18 19l-.833-2m-3.334 0l1.667-4l1.667 4m-3.334 0h3.334"/></g></svg>                        <span>Languages</span>
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
