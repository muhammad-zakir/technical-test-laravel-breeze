<script setup>
import { UploadOutlined } from '@ant-design/icons-vue';
import { useForm } from '@inertiajs/vue3';
import {
    Button as AButton,
    Form as AForm,
    FormItem as AFormItem,
    Input as AInput,
    Modal as AModal,
    message,
    Upload as AUpload
} from 'ant-design-vue';
import { ref, watch, computed } from 'vue';

const props = defineProps({
    open: Boolean,
    company: {
        type: Object,
        default: null,
    },
});

const emit = defineEmits(['close']);

const isEditing = computed(() => !!props.company);
const title = computed(() => isEditing.value ? 'Edit Company' : 'Add Company');

const form = useForm({
    name: '',
    email: '',
    logo: null,
    website: '',
});

const fileList = ref([]);

watch(() => props.open, (val) => {
    if (val) {
        if (props.company) {
            form.name = props.company.name || '';
            form.email = props.company.email || '';
            form.website = props.company.website || '';
            form.logo = null;
            fileList.value = [];
        } else {
            form.reset();
            form.clearErrors();
            fileList.value = [];
        }
    }
});

const beforeUpload = (file) => {
    const isImage = file.type.startsWith('image/');
    if (!isImage) {
        message.error('You can only upload image files!');
        return false;
    }
    const isImageSizeLessThan2MB = file.size / 1024 / 1024 < 2;
    if (!isImageSizeLessThan2MB) {
        message.error('Image must be smaller than 2MB!');
        return false;
    }
    form.logo = file;
    fileList.value = [file];
    return false;
};

const handleRemove = () => {
    form.logo = null;
    fileList.value = [];
};

const handleSubmit = () => {
    if (isEditing.value) {
        form.transform((data) => ({
            ...data,
            _method: 'put',
            logo: data.logo === null ? '' : data.logo,
        })).post(route('companies.update', props.company.id), {
            forceFormData: true,
            preserveScroll: true,
            onSuccess: () => {
                message.success('Company updated successfully.');
                emit('close');
            },
        });
    } else {
        form.transform((data) => ({
            ...data,
            logo: data.logo === null ? '' : data.logo,
        })).post(route('companies.store'), {
            forceFormData: true,
            preserveScroll: true,
            onSuccess: () => {
                message.success('Company created successfully.');
                emit('close');
            },
        });
    }
};
</script>

<template>
    <AModal
        :open="open"
        :title="title"
        @cancel="emit('close')"
        :footer="null"
        :destroy-on-close="true"
        width="520px"
    >
        <AForm
            layout="vertical"
            @submit.prevent="handleSubmit"
            class="mt-4"
        >
            <AFormItem
                label="Name"
                :validate-status="form.errors.name ? 'error' : ''"
                :help="form.errors.name"
                required
            >
                <AInput
                    v-model:value="form.name"
                    placeholder="Enter company name"
                    size="large"
                />
            </AFormItem>

            <AFormItem
                label="Email"
                :validate-status="form.errors.email ? 'error' : ''"
                :help="form.errors.email"
            >
                <AInput
                    v-model:value="form.email"
                    placeholder="Enter email address"
                    size="large"
                    type="email"
                />
            </AFormItem>

            <AFormItem
                label="Logo"
                :validate-status="form.errors.logo ? 'error' : ''"
                :help="form.errors.logo"
            >
                <div v-if="isEditing && company.logo && !form.logo" class="mb-2">
                    <img :src="company.logo" alt="Current logo" class="h-16 w-16 object-cover rounded-lg border" />
                    <p class="text-xs text-gray-400 mt-1">Current logo</p>
                </div>
                <AUpload
                    :file-list="fileList"
                    :before-upload="beforeUpload"
                    @remove="handleRemove"
                    :max-count="1"
                    accept="image/*"
                >
                    <AButton>
                        <UploadOutlined />
                        {{ isEditing ? 'Change Logo' : 'Upload Logo' }}
                    </AButton>
                </AUpload>
            </AFormItem>

            <AFormItem
                label="Website"
                :validate-status="form.errors.website ? 'error' : ''"
                :help="form.errors.website"
            >
                <AInput
                    v-model:value="form.website"
                    placeholder="https://example.com"
                    size="large"
                />
            </AFormItem>

            <AFormItem class="!mb-0 flex justify-end">
                <div class="flex gap-2">
                    <AButton @click="emit('close')">Cancel</AButton>
                    <AButton
                        type="primary"
                        html-type="submit"
                        :loading="form.processing"
                    >
                        {{ isEditing ? 'Update' : 'Create' }}
                    </AButton>
                </div>
            </AFormItem>
        </AForm>
    </AModal>
</template>
