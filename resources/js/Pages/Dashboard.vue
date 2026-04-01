<script setup>
import { BankOutlined, TeamOutlined } from '@ant-design/icons-vue';
import { Head, usePage } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import {
    Card as ACard,
    Col as ACol,
    Row as ARow,
    Typography
} from 'ant-design-vue';
import { computed } from 'vue';

const { Title: ATypographyTitle, Paragraph: ATypographyParagraph } = Typography;

const page = usePage();
const user = computed(() => page.props.auth.user);
const isAdmin = computed(() => user.value?.role === 'admin');
</script>

<template>
    <Head title="Dashboard" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="text-xl font-semibold leading-tight text-gray-800">
                Dashboard
            </h2>
        </template>

        <div class="space-y-6">
            <ACard class="shadow-sm">
                <ATypographyTitle :level="3" class="!mb-1">
                    Welcome back, {{ user?.name }}!
                </ATypographyTitle>
                <ATypographyParagraph class="!mb-0 text-gray-500">
                    You are logged in as <a-tag :color="isAdmin ? 'blue' : 'default'">{{ user?.role }}</a-tag>
                </ATypographyParagraph>
            </ACard>

            <ARow v-if="isAdmin" :gutter="[16, 16]">
                <ACol :xs="24" :sm="12" :lg="8">
                    <ACard class="shadow-sm hover:shadow-md transition-shadow">
                        <div class="flex items-center justify-between p-2">
                            <div class="flex items-center gap-3">
                                <BankOutlined class="text-2xl text-[#1677ff]" />
                                <span class="text-lg font-medium text-gray-700">Companies</span>
                            </div>
                            <a-button type="link" size="small" @click="$inertia.visit(route('companies.index'))">
                                Manage →
                            </a-button>
                        </div>
                    </ACard>
                </ACol>
                <ACol :xs="24" :sm="12" :lg="8">
                    <ACard class="shadow-sm hover:shadow-md transition-shadow">
                        <div class="flex items-center justify-between p-2">
                            <div class="flex items-center gap-3">
                                <TeamOutlined class="text-2xl text-[#52c41a]" />
                                <span class="text-lg font-medium text-gray-700">Employees</span>
                            </div>
                            <a-button type="link" size="small" @click="$inertia.visit(route('employees.index'))">
                                Manage →
                            </a-button>
                        </div>
                    </ACard>
                </ACol>
            </ARow>

            <ACard v-if="!isAdmin" class="shadow-sm">
                <a-result
                    status="info"
                    title="Limited Access"
                    sub-title="You have basic user access. Contact an administrator to get admin privileges."
                />
            </ACard>
        </div>
    </AuthenticatedLayout>
</template>
