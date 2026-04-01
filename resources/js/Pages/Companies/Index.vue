<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import CompanyFormModal from './CompanyFormModal.vue';
import {
    DeleteOutlined,
    EditOutlined,
    LinkOutlined,
    PlusOutlined,
    SearchOutlined
} from '@ant-design/icons-vue';
import { Head, router } from '@inertiajs/vue3';
import {
    Button as AButton,
    Card as ACard,
    Input as AInput,
    Image as AImage,
    message,
    Popconfirm as APopconfirm,
    Space as ASpace,
    Table as ATable,
    Tag as ATag
} from 'ant-design-vue';
import { ref, computed } from 'vue';

const props = defineProps({
    companies: Object,
    pagination: Object,
    filters: Object,
});

const search = ref(props.filters?.search || '');
const showModal = ref(false);
const editingCompany = ref(null);

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
        title: 'Name',
        dataIndex: 'name',
        key: 'name',
        sorter: true,
    },
    {
        title: 'Email',
        dataIndex: 'email',
        key: 'email',
        sorter: true,
    },
    {
        title: 'Logo',
        dataIndex: 'logo',
        key: 'logo',
        width: 100,
    },
    {
        title: 'Website',
        dataIndex: 'website',
        key: 'website',
    },
    {
        title: 'Action',
        key: 'action',
        width: 150,
        fixed: 'right',
    },
];

const tableData = computed(() => props.companies?.data || []);

const paginationConfig = computed(() => ({
    current: props.pagination?.current_page || 1,
    pageSize: props.pagination?.per_page || 10,
    total: props.pagination?.total || 0,
    showSizeChanger: true,
    showTotal: (total, range) => `${range[0]}-${range[1]} of ${total} items`,
    pageSizeOptions: ['10', '25', '50'],
}));

const handleTableChange = (pagination, filters, sorter) => {
    router.get(route('companies.index'), {
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
        router.get(route('companies.index'), {
            search: search.value,
        }, {
            preserveState: true,
            preserveScroll: true,
        });
    }, 300);
};

const openCreateModal = () => {
    editingCompany.value = null;
    showModal.value = true;
};

const openEditModal = (company) => {
    editingCompany.value = company;
    showModal.value = true;
};

const closeModal = () => {
    showModal.value = false;
    editingCompany.value = null;
};

const handleDelete = (company) => {
    router.delete(route('companies.destroy', company.id), {
        preserveScroll: true,
        onSuccess: () => {
            message.success('Company deleted successfully.');
        },
    });
};
</script>

<template>
    <Head title="Companies" />

    <AuthenticatedLayout>
        <template #header>
            <div class="flex items-center justify-between">
                <h2 class="text-xl font-semibold leading-tight text-gray-800">
                    Companies
                </h2>
                <AButton type="primary" @click="openCreateModal">
                    <PlusOutlined />
                    Add Company
                </AButton>
            </div>
        </template>

        <ACard class="shadow-sm">
            <!-- Search Bar -->
            <div class="mb-4">
                <AInput
                    v-model:value="search"
                    placeholder="Search companies..."
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
                    <template v-if="column.key === 'logo'">
                        <AImage
                            v-if="record.logo"
                            :src="record.logo"
                            :width="40"
                            :height="40"
                            class="rounded object-cover"
                            :preview="{ mask: 'View' }"
                        />
                        <ATag v-else color="default">No logo</ATag>
                    </template>

                    <template v-if="column.key === 'website'">
                        <a
                            v-if="record.website"
                            :href="record.website"
                            target="_blank"
                            rel="noopener noreferrer"
                            class="text-blue-500 hover:text-blue-700 flex items-center gap-1"
                        >
                            <LinkOutlined />
                            {{ record.website }}
                        </a>
                        <span v-else class="text-gray-400">—</span>
                    </template>

                    <template v-if="column.key === 'email'">
                        <span v-if="record.email">{{ record.email }}</span>
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
                                title="Are you sure you want to delete this company?"
                                description="This action cannot be undone. All employees under this company will also be deleted."
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
        <CompanyFormModal
            :open="showModal"
            :company="editingCompany"
            @close="closeModal"
        />
    </AuthenticatedLayout>
</template>
