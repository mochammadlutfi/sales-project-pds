<template>
    <el-card class="shadow-sm sm:rounded-lg" v-loading="loading">
        <el-form :model="form" @submit.prevent="onSubmit" label-position="top">
            <el-row :gutter="20">
                <el-col :md="12">
                    <el-form-item :label="$t('setting.general.app_name')" :error="errors.app_name">
                        <el-input v-model="form.app_name" />
                    </el-form-item>
                    <el-form-item :label="$t('setting.general.company_email')" :error="errors.company_email">
                        <el-input v-model="form.company_email" />
                    </el-form-item>
                </el-col>
                <el-col :md="12">
                    <el-form-item :label="$t('setting.general.company_name')" :error="errors.company_name">
                        <el-input v-model="form.company_name" />
                    </el-form-item>
                    <el-form-item :label="$t('setting.general.company_phone')" :error="errors.company_phone">
                        <el-input v-model="form.company_phone" />
                    </el-form-item>
                </el-col>
            </el-row>
            <el-form-item :label="$t('setting.general.company_address')" :error="errors.company_address">
                <el-input type="textarea" v-model="form.company_address" />
            </el-form-item>
            <el-row :gutter="20">
                <el-col :span="12">
                    <el-form-item :label="$t('setting.general.default_branch')">
                        <select-branch v-model="form.branch"/>
                    </el-form-item>
                </el-col>
                <el-col :span="12">
                    <el-form-item :label="$t('setting.general.date_format')">
                        <select-date-format v-model="form.date_format"/>
                    </el-form-item>
                    <el-form-item :label="$t('setting.general.time_format')">
                        <select-time-format v-model="form.time_format"/>
                    </el-form-item>
                </el-col>
            </el-row>
            <el-row :gutter="20">
                <el-col :md="6">
                    <el-form-item :label="$t('setting.general.logo_light')" :error="errors.company_address">
                        <upload-image v-model="form.logo_light"/>
                    </el-form-item>
                </el-col>
                <el-col :md="6">
                    <el-form-item :label="$t('setting.general.logo_dark')" :error="errors.company_address">
                        <upload-image v-model="form.logo_dark"/>
                    </el-form-item>
                </el-col>
                <el-col :md="6">
                    <el-form-item :label="$t('setting.general.logo_light_sm')" :error="errors.company_address">
                        <upload-image v-model="form.logo_light_sm"/>
                    </el-form-item>
                </el-col>
                <el-col :md="6">
                    <el-form-item :label="$t('setting.general.logo_dark_sm')" :error="errors.company_address">
                        <upload-image v-model="form.logo_dark_sm"/>
                    </el-form-item>
                </el-col>
            </el-row>
            <div class="text-right">
                <el-button native-type="submit" type="primary">
                    <i class="mgc_check_fill me-2"></i>
                    {{ $t('save') }}
                </el-button>
            </div>
        </el-form>
    </el-card>
</template>

<script setup>
import { defineProps, onMounted, defineEmits, ref } from 'vue';
import SelectBranch from '@/Components/Form/SelectBranch.vue';
import SelectDateFormat from '@/Components/Form/SelectDateFormat.vue';
import SelectTimeFormat from '@/Components/Form/SelectTimeFormat.vue';
import UploadImage from '@/Components/Form/UploadImage.vue';
import { useI18n } from 'vue-i18n';
const t = useI18n();

const emit = defineEmits(['childinit']);
const props = defineProps({
  title: {
    type: String,
    default: '',
  },
});


const form = ref({
    app_name : null,
    company_name : null,
    company_email : null,
    company_address : null,
    logo_light : 'SSL',
    logo_dark : null,
    logo_light_sm : null,
    logo_dark_sm : null,
    branch : 1,
    time_zone : null,
    date_format : "d-m-Y",
    time_format : "HH:mm",
});
const errors = ref({});
const loading = ref(false);

const onSubmit = async () => {
    loading.value = true;
    const url = '/settings/email/update';
    try {
        const response = await axios.post(url, form.value);
        console.log(response.data.result);
        ElMessage({message: t('success_msg'), type: 'success'});
    } catch (error) {
        errors.value = error.validation;
        ElMessage({message: t('error_msg'), type: 'error'});
    } finally {
        loading.value = false;
    }
};

const fetchData = async () => {
  try {
    errors.value = {};
    loading.value = true;
    const response = await axios.get('/settings/general');
    if (response.status === 200) {
      form.value.app_name = response.data.app_name;
      form.value.company_name = response.data.company_name;
      form.value.company_email = response.data.company_email;
      form.value.company_phone = response.data.company_phone;
      form.value.company_address = response.data.company_address;
      form.value.logo_light = response.data.logo_light;
      form.value.logo_dark = response.data.logo_dark;
      form.value.logo_light_sm = response.data.logo_light_sm;
      form.value.logo_dark_sm = response.data.logo_dark_sm;
      form.value.branch = response.data.branch;
    }
  } catch (error) {
    console.error(error);
  } finally {
    loading.value = false;
  }
};
onMounted(() => {
  emit('childinit', 'general_setting');
  fetchData();
});
</script>


