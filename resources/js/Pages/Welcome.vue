<script setup>
import { Head, Link } from '@inertiajs/vue3';
import { 
    BankOutlined,
    DashboardOutlined,
    LoginOutlined,
    SafetyCertificateOutlined,
    TeamOutlined
} from '@ant-design/icons-vue';
import {
    Button as AButton,
    Card as ACard,
    Col as ACol,
    Layout as ALayout,
    LayoutHeader as ALayoutHeader,
    LayoutContent as ALayoutContent,
    LayoutFooter as ALayoutFooter,
    Row as ARow,
    Space as ASpace,
    TypographyTitle as ATitle,
    TypographyParagraph as AParagraph,
} from 'ant-design-vue';

defineProps({
    canLogin: {
        type: Boolean,
    }
});
</script>

<template>
    <Head title="Welcome to Friendly HRIS" />
    <ALayout class="min-h-screen bg-gray-50/50">
        <ALayoutHeader class="bg-white/80 backdrop-blur-md border-b border-gray-100 px-6 flex items-center justify-between sticky top-0 z-50">
            <div class="flex items-center gap-3">
                <div class="bg-blue-600 text-white w-8 h-8 rounded-lg flex items-center justify-center shadow-lg shadow-blue-500/30">
                    <TeamOutlined class="text-lg" />
                </div>
                <span class="text-xl font-extrabold tracking-tight bg-gradient-to-r from-blue-600 to-indigo-600 bg-clip-text text-transparent">
                    Friendly HRIS
                </span>
            </div>
            
            <nav v-if="canLogin">
                <ASpace>
                    <Link
                        v-if="$page.props.auth.user"
                        :href="route('dashboard')"
                    >
                        <AButton type="primary" shape="round" size="large">
                            <template #icon><DashboardOutlined /></template>
                            Dashboard
                        </AButton>
                    </Link>

                    <template v-else>
                        <Link :href="route('login')">
                            <AButton type="primary" shape="round" size="large">
                                <template #icon><LoginOutlined /></template>
                                Log In
                            </AButton>
                        </Link>
                    </template>
                </ASpace>
            </nav>
        </ALayoutHeader>

        <ALayoutContent>
            <div class="relative overflow-hidden bg-white pt-24 pb-32">
                <div class="absolute inset-0 bg-gradient-to-br from-blue-50/80 to-indigo-50/40"></div>
                <div class="absolute -top-32 -right-32 w-[600px] h-[600px] bg-blue-400/10 rounded-full blur-3xl mix-blend-multiply"></div>
                <div class="absolute -bottom-32 -left-32 w-[600px] h-[600px] bg-indigo-400/10 rounded-full blur-3xl mix-blend-multiply"></div>
                
                <div class="relative max-w-7xl mx-auto px-6 lg:flex lg:items-center lg:gap-x-10">
                    <div class="mx-auto max-w-3xl text-center lg:text-left animate-fade-in-up">
                        <ATitle :level="1" class="!text-5xl sm:!text-6xl !font-extrabold !leading-[1.15] tracking-tight mb-6">
                            Modern <span class="bg-gradient-to-r from-blue-600 to-indigo-600 bg-clip-text text-transparent">Corporate</span> Management
                        </ATitle>
                        <AParagraph class="!text-xl !text-gray-500 mb-10 max-w-2xl mx-auto lg:mx-0 leading-relaxed font-light">
                            A powerful, streamlined platform designed specifically for tracking company entities and organizing human resources with integrated secure access controls.
                        </AParagraph>
                    </div>
                </div>
            </div>

            <div class="max-w-7xl mx-auto px-6 py-24">
                <div class="text-center mb-16">
                    <ATitle :level="2" class="!text-3xl !font-bold">Everything you need</ATitle>
                    <AParagraph class="!text-lg !text-gray-500">Simplify your administrative workflow with carefully crafted tools.</AParagraph>
                </div>

                <ARow :gutter="[32, 32]">
                    <ACol :xs="24" :md="8">
                        <ACard hoverable class="h-full border-0 shadow-sm hover:shadow-xl transition-all duration-300 rounded-3xl group overflow-hidden bg-white/50 backdrop-blur-sm ring-1 ring-gray-100">
                            <div class="w-14 h-14 bg-gradient-to-br from-blue-50 to-blue-100 text-blue-600 rounded-2xl flex items-center justify-center mb-6 group-hover:-translate-y-1 transition-transform duration-300">
                                <BankOutlined class="text-2xl" />
                            </div>
                            <ATitle :level="4" class="!font-bold !mb-3">Company Registry</ATitle>
                            <AParagraph class="!text-gray-500 !leading-relaxed">Manage all your corporate entities gracefully, complete with high-resolution active logo handling, websites tracking, and deep structural relational links.</AParagraph>
                        </ACard>
                    </ACol>

                    <ACol :xs="24" :md="8">
                        <ACard hoverable class="h-full border-0 shadow-sm hover:shadow-xl transition-all duration-300 rounded-3xl group overflow-hidden bg-white/50 backdrop-blur-sm ring-1 ring-gray-100 relative">
                            <div class="w-14 h-14 bg-gradient-to-br from-indigo-50 to-indigo-100 text-indigo-600 rounded-2xl flex items-center justify-center mb-6 group-hover:-translate-y-1 transition-transform duration-300">
                                <TeamOutlined class="text-2xl" />
                            </div>
                            <ATitle :level="4" class="!font-bold !mb-3">Employee Directory</ATitle>
                            <AParagraph class="!text-gray-500 !leading-relaxed">Maintain detailed, highly-specific employee profiles linked perfectly directly to their respective corporate hierarchy, mapped effortlessly natively.</AParagraph>
                        </ACard>
                    </ACol>

                    <ACol :xs="24" :md="8">
                        <ACard hoverable class="h-full border-0 shadow-sm hover:shadow-xl transition-all duration-300 rounded-3xl group overflow-hidden bg-white/50 backdrop-blur-sm ring-1 ring-gray-100 relative">
                            <div class="w-14 h-14 bg-gradient-to-br from-purple-50 to-purple-100 text-purple-600 rounded-2xl flex items-center justify-center mb-6 group-hover:-translate-y-1 transition-transform duration-300">
                                <SafetyCertificateOutlined class="text-2xl" />
                            </div>
                            <ATitle :level="4" class="!font-bold !mb-3">Secure Gateways</ATitle>
                            <AParagraph class="!text-gray-500 !leading-relaxed">Enterprise-grade role-based (RBAC) authentication logic shielding native application endpoints effortlessly from unauthorized viewership.</AParagraph>
                        </ACard>
                    </ACol>
                </ARow>
            </div>
        </ALayoutContent>

        <ALayoutFooter class="bg-white border-t border-gray-100 py-10 text-gray-500">
            <div class="max-w-7xl mx-auto px-6 text-center sm:flex sm:items-center sm:justify-between sm:text-left">
                <div>
                    <div>
                        <span class="font-bold text-gray-800 tracking-tight">Friendly HRIS</span>
                        <span class="mx-2 text-gray-300">|</span>
                        Powered by <span class="font-medium text-gray-700">Ant Design Vue 4</span>
                    </div>
                    <div class="mt-1 text-sm text-gray-400">
                        &copy; {{ new Date().getFullYear() }} Friendly HRIS Inc.
                    </div>
                </div>
            </div>
        </ALayoutFooter>
    </ALayout>
</template>

<style scoped>
@keyframes fadeInUp {
    from {
        opacity: 0;
        transform: translateY(20px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.animate-fade-in-up {
    animation: fadeInUp 0.8s ease-out forwards;
}

:deep(.ant-btn-primary) {
    box-shadow: 0 4px 14px 0 rgba(37, 99, 235, 0.39);
}

:deep(.ant-btn-primary:hover) {
    transform: translateY(-2px);
    box-shadow: 0 6px 20px rgba(37, 99, 235, 0.5);
}
</style>
