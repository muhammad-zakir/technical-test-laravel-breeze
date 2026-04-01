<script setup>
import { useForm } from '@inertiajs/vue3';
import {
    Button as AButton,
    Form as AForm,
    FormItem as AFormItem,
    Input as AInput,
    Modal as AModal,
    message,
    Select as ASelect
} from 'ant-design-vue';
import { watch, computed } from 'vue';

const props = defineProps({
    open: Boolean,
    employee: {
        type: Object,
        default: null,
    },
    companies: {
        type: [Array, Object],
        default: () => [],
    },
});

const emit = defineEmits(['close']);

const isEditing = computed(() => !!props.employee);
const title = computed(() => isEditing.value ? 'Edit Employee' : 'Add Employee');

const form = useForm({
    first_name: '',
    last_name: '',
    company_id: undefined,
    email: '',
    phone: '',
});

watch(() => props.open, (val) => {
    if (val) {
        if (props.employee) {
            form.first_name = props.employee.first_name || '';
            form.last_name = props.employee.last_name || '';
            form.company_id = props.employee.company_id || undefined;
            form.email = props.employee.email || '';
            form.phone = props.employee.phone || '';
        } else {
            form.reset();
            form.clearErrors();
        }
    }
});

const handleSubmit = () => {
    if (isEditing.value) {
        form.put(route('employees.update', props.employee.id), {
            preserveScroll: true,
            onSuccess: () => {
                message.success('Employee updated successfully.');
                emit('close');
            },
        });
    } else {
        form.post(route('employees.store'), {
            preserveScroll: true,
            onSuccess: () => {
                message.success('Employee created successfully.');
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
                label="First Name"
                :validate-status="form.errors.first_name ? 'error' : ''"
                :help="form.errors.first_name"
                required
            >
                <AInput
                    v-model:value="form.first_name"
                    placeholder="Enter first name"
                    size="large"
                />
            </AFormItem>

            <AFormItem
                label="Last Name"
                :validate-status="form.errors.last_name ? 'error' : ''"
                :help="form.errors.last_name"
                required
            >
                <AInput
                    v-model:value="form.last_name"
                    placeholder="Enter last name"
                    size="large"
                />
            </AFormItem>

            <AFormItem
                label="Company"
                :validate-status="form.errors.company_id ? 'error' : ''"
                :help="form.errors.company_id"
                required
            >
                <ASelect
                    v-model:value="form.company_id"
                    placeholder="Select a company"
                    size="large"
                    show-search
                    :filter-option="(input, option) =>
                        option.label.toLowerCase().includes(input.toLowerCase())
                    "
                    :options="(companies?.data || companies || []).map(c => ({ value: c.id, label: c.name }))"
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
                label="Phone"
                :validate-status="form.errors.phone ? 'error' : ''"
                :help="form.errors.phone"
            >
                <AInput
                    v-model:value="form.phone"
                    placeholder="Enter phone number"
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
