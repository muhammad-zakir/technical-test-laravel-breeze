<script setup>
import {
    EditOutlined,
    DeleteOutlined,
    PlusOutlined,
    SearchOutlined
} from '@ant-design/icons-vue';
import CompanyDetailModal from '@/Components/CompanyDetailModal.vue';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import EmployeeFormModal from './EmployeeFormModal.vue';
import { Head, router } from '@inertiajs/vue3';
import {
    Button as AButton,
    Card as ACard,
    Input as AInput,
    message,
    Popconfirm as APopconfirm,
    Space as ASpace,
    Table as ATable
} from 'ant-design-vue';
import { ref, computed } from 'vue';

const props = defineProps({
    employees: Object,
    pagination: Object,
    companies: [Array, Object],
    filters: Object,
});

const search = ref(props.filters?.search || '');
const showModal = ref(false);
const editingEmployee = ref(null);
const showCompanyModal = ref(false);
const selectedCompany = ref(null);

const columns = [
    {
        title: '#',
        key: 'index',
        width: 60,
        customRender: ({ index }) => {
            return (props.pagination.current_page - 1) * props.pagination.per_page + index + 1;
        },
    },
    {
        title: 'Full Name',
        dataIndex: 'full_name',
        key: 'full_name',
        sorter: true,
    },
    {
        title: 'Company',
        key: 'company',
    },
    {
        title: 'Email',
        dataIndex: 'email',
        key: 'email',
        sorter: true,
    },
    {
        title: 'Phone',
        dataIndex: 'phone',
        key: 'phone',
    },
    {
        title: 'Action',
        key: 'action',
        width: 150,
        fixed: 'right',
    },
];

const tableData = computed(() => props.employees?.data || []);

const paginationConfig = computed(() => ({
    current: props.pagination?.current_page || 1,
    pageSize: props.pagination?.per_page || 10,
    total: props.pagination?.total || 0,
    showSizeChanger: true,
    showTotal: (total, range) => `${range[0]}-${range[1]} of ${total} items`,
    pageSizeOptions: ['10', '25', '50'],
}));

const handleTableChange = (pagination, filters, sorter) => {
    router.get(route('employees.index'), {
        page: pagination.current,
        per_page: pagination.pageSize,
        sort_field: sorter.field || 'id',
        sort_order: sorter.order || 'ascend',
        search: search.value,
    }, {
        preserveState: true,
        preserveScroll: true,
    });
};

let searchTimeout = null;
const handleSearch = () => {
    clearTimeout(searchTimeout);
    searchTimeout = setTimeout(() => {
        router.get(route('employees.index'), {
            search: search.value,
        }, {
            preserveState: true,
            preserveScroll: true,
        });
    }, 300);
};

const openCreateModal = () => {
    editingEmployee.value = null;
    showModal.value = true;
};

const openEditModal = (employee) => {
    editingEmployee.value = employee;
    showModal.value = true;
};

const closeModal = () => {
    showModal.value = false;
    editingEmployee.value = null;
};

const openCompanyDetail = (company) => {
    selectedCompany.value = company;
    showCompanyModal.value = true;
};

const closeCompanyModal = () => {
    showCompanyModal.value = false;
    selectedCompany.value = null;
};

const handleDelete = (employee) => {
    router.delete(route('employees.destroy', employee.id), {
        preserveScroll: true,
        onSuccess: () => {
            message.success('Employee deleted successfully.');
        },
    });
};
</script>

<template>
    <Head title="Employees" />

    <AuthenticatedLayout>
        <template #header>
            <div class="flex items-center justify-between">
                <h2 class="text-xl font-semibold leading-tight text-gray-800">
                    Employees
                </h2>
                <AButton type="primary" @click="openCreateModal">
                    <PlusOutlined />
                    Add Employee
                </AButton>
            </div>
        </template>

        <ACard class="shadow-sm">
            <!-- Search Bar -->
            <div class="mb-4">
                <AInput
                    v-model:value="search"
                    placeholder="Search employees..."
                    @input="handleSearch"
                    allow-clear
                    size="large"
                    class="max-w-md"
                >
                    <template #prefix>
                        <SearchOutlined class="text-gray-400" />
                    </template>
                </AInput>
            </div>

            <!-- Data Table -->
            <ATable
                :columns="columns"
                :data-source="tableData"
                :pagination="paginationConfig"
                :row-key="record => record.id"
                @change="handleTableChange"
                :scroll="{ x: 800 }"
                bordered
                size="middle"
            >
                <template #bodyCell="{ column, record }">
                    <template v-if="column.key === 'company'">
                        <a
                            v-if="record.company"
                            @click.prevent="openCompanyDetail(record.company)"
                            href="#"
                            class="text-blue-500 hover:text-blue-700 cursor-pointer font-medium"
                        >
                            {{ record.company.name }}
                        </a>
                        <span v-else class="text-gray-400">—</span>
                    </template>

                    <template v-if="column.key === 'email'">
                        <span v-if="record.email">{{ record.email }}</span>
                        <span v-else class="text-gray-400">—</span>
                    </template>

                    <template v-if="column.key === 'phone'">
                        <span v-if="record.phone">{{ record.phone }}</span>
                        <span v-else class="text-gray-400">—</span>
                    </template>

                    <template v-if="column.key === 'action'">
                        <ASpace>
                            <AButton
                                type="primary"
                                ghost
                                size="small"
                                @click="openEditModal(record)"
                            >
                                <EditOutlined />
                                Edit
                            </AButton>
                            <APopconfirm
                                title="Are you sure you want to delete this employee?"
                                description="This action cannot be undone."
                                @confirm="handleDelete(record)"
                                ok-text="Yes, Delete"
                                cancel-text="Cancel"
                                ok-type="danger"
                            >
                                <AButton danger size="small">
                                    <DeleteOutlined />
                                    Delete
                                </AButton>
                            </APopconfirm>
                        </ASpace>
                    </template>
                </template>
            </ATable>
        </ACard>

        <!-- Create/Edit Modal -->
        <EmployeeFormModal
            :open="showModal"
            :employee="editingEmployee"
            :companies="companies"
            @close="closeModal"
        />

        <!-- Company Detail Modal -->
        <CompanyDetailModal
            :open="showCompanyModal"
            :company="selectedCompany"
            @close="closeCompanyModal"
        />
    </AuthenticatedLayout>
</template>
