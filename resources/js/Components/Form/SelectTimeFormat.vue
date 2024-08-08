<template>
    <el-select v-model="selected" value-key="key"
    filterable 
    clearable 
    remote
    @change="selectChange"
    autocomplete="off"
    :loading="isLoading"
    :placeholder="placeholder">
        <template #label="{ label }">
            <span>{{ $t('hours_select', {time : label.time, format : label.format}) }}: </span>
        </template>
        <el-option
            v-for="(v, key) in dataList"
            :key="key"
            :label="$t('hours_select', {time : v.time, format : v.format})"
            :value="key"
        >
        <span class="float-left">{{ $t('hours_select', {time : v.time, format : v.format}) }}</span>
        <span
            style="
            float: right;
            color: var(--el-text-color-secondary);
            font-size: 13px;
            "
        >
            {{ dateFormat(key) }}
        </span>
        </el-option>
    </el-select>
</template>

<script setup>
import { defineProps, ref, defineEmits, watch } from 'vue';
import dayjs from 'dayjs';
import { useI18n } from 'vue-i18n';
const { t } = useI18n();

 
const props = defineProps({
  modelValue: String,
  placeholder: {
    type: String,
    default: '',
  },
});


const emit = defineEmits(['update:modelValue']);

const dataList = ref({
    "hh:mm A" : {
        time : 12,
        format : 'hh:mm A'
    },
    'hh:mm a' : {
        time : 12,
        format : 'hh:mm a'
    },
    'hh:mm:ss A' : {
        time : 12,
        format : 'hh:mm:ss A'
    },
    'hh:mm:ss a' : {
        time : 12,
        format : 'hh:mm::ss a'
    },
    'HH:mm ' : {
        time : 24,
        format : 'HH:mm'
    },
    'HH:mm:ss' : {
        time : 24,
        format : 'HH:mm:ss'
    },
});
const selected = ref(props.modelValue);
const isLoading = ref(false);

const dateFormat = (format) =>{
    return dayjs().format(format);
}

watch(() => props.modelValue, (newValue) => {
    selected.value = newValue;
});

const selectChange = (v) => {
  emit('update:modelValue', v);
};
</script>