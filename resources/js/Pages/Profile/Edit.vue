<template>
    <el-card class="shadow-sm sm:rounded-lg" v-loading="loading">
        <el-form :model="form" @submit.prevent="onSubmit" label-position="top">
            <el-row :gutter="20">
                <el-col :md="8">
                    <el-form-item :label="$t('avatar')" :error="errors.avatar">
                        <upload-image v-model="form.avatar"/>
                    </el-form-item>
                </el-col>
                <el-col :md="16">
                    <el-form-item :label="$t('name')" :error="errors.name">
                        <el-input v-model="form.name" />
                    </el-form-item>
                    <el-form-item :label="$t('username')" :error="errors.username">
                        <el-input v-model="form.username" />
                    </el-form-item>
                    <el-form-item :label="$t('email')" :error="errors.email">
                        <el-input v-model="form.email" />
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
import { onMounted, ref, computed } from 'vue';
import UploadImage from '@/Components/Form/UploadImage.vue';
import { useI18n } from 'vue-i18n';
import { useAuthStore } from '@/stores/auth';
const t = useI18n();
const auth = useAuthStore();


const emit = defineEmits(['childinit']);
const props = defineProps({
  title: {
    type: String,
    default: '',
  },
});


const form = ref({
    avatar : null,
    name : null,
    username : null,
    email : null,
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
    const response = await axios.get('/profile');
    if (response.status === 200) {
      form.value.avatar = response.data.avatar;
      form.value.name = response.data.name;
      form.value.username = response.data.username;
      form.value.email = response.data.email;
    }
  } catch (error) {
    console.error(error);
  } finally {
    loading.value = false;
  }
};

const fillData = async () => {
    const { data } = await axios.get('/profile');
    form.value.name = data.name;
    form.value.email = data.email;
    form.value.username = data.username;
    form.value.avatar = data.avatar;
};
onMounted(() => {
    emit('childinit', 'Edit Profile');
    // const user = computed(() => auth.user);
    // form.value.name = user.name;
    // form.value.avatar = user.avatar;
    // form.value.username = user.username;
    // form.value.avatar = user.avatar;

    fillData();
});

</script>


