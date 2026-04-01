<script setup>
import {
    BankOutlined,
    DashboardOutlined,
    LogoutOutlined,
    MenuFoldOutlined,
    MenuUnfoldOutlined,
    TeamOutlined,
    UserOutlined,
} from '@ant-design/icons-vue';
import { Link, usePage, router } from '@inertiajs/vue3';
import {
    Avatar as AAvatar,
    Button as AButton,
    Dropdown as ADropdown,
    Layout as ALayout,
    LayoutHeader as ALayoutHeader,
    LayoutSider as ALayoutSider,
    LayoutContent as ALayoutContent,
    Menu as AMenu,
    MenuItem as AMenuItem,
    message,
} from 'ant-design-vue';
import { ref, computed } from 'vue';

const page = usePage();
const collapsed = ref(false);

const user = computed(() => page.props.auth.user);
const isAdmin = computed(() => user.value?.role === 'admin');
const flash = computed(() => page.props.flash);

if (flash.value?.success) {
    message.success(flash.value.success);
}

const selectedKeys = computed(() => {
    const url = page.url;
    if (url.startsWith('/companies')) return ['companies'];
    if (url.startsWith('/employees')) return ['employees'];
    if (url.startsWith('/dashboard')) return ['dashboard'];
    return ['dashboard'];
});

const handleMenuClick = ({ key }) => {
    router.visit(route(key === 'dashboard' ? 'dashboard' : `${key}.index`));
};

const handleLogout = () => {
    router.post(route('logout'));
};
</script>

<template>
    <ALayout class="min-h-screen">
        <ALayoutSider
            v-model:collapsed="collapsed"
            :trigger="null"
            collapsible
            class="!bg-[#001529]"
            breakpoint="lg"
            collapsed-width="80"
        >
            <div class="flex items-center justify-center h-16 px-4">
                <Link :href="route('dashboard')" class="flex items-center gap-2 no-underline">
                    <div class="w-8 h-8 rounded-lg bg-blue-500 flex items-center justify-center">
                        <span class="text-white font-bold text-sm">GR</span>
                    </div>
                    <span v-if="!collapsed" class="text-white font-semibold text-lg whitespace-nowrap">
                        GR Tech
                    </span>
                </Link>
            </div>

            <AMenu
                :selected-keys="selectedKeys"
                theme="dark"
                mode="inline"
                @click="handleMenuClick"
            >
                <AMenuItem key="dashboard">
                    <DashboardOutlined />
                    <span>Dashboard</span>
                </AMenuItem>
                <AMenuItem v-if="isAdmin" key="companies">
                    <BankOutlined />
                    <span>Companies</span>
                </AMenuItem>
                <AMenuItem v-if="isAdmin" key="employees">
                    <TeamOutlined />
                    <span>Employees</span>
                </AMenuItem>
            </AMenu>
        </ALayoutSider>

        <ALayout>
            <ALayoutHeader class="!bg-white !px-6 flex items-center justify-between shadow-sm" style="padding: 0 24px;">
                <AButton
                    type="text"
                    @click="collapsed = !collapsed"
                    class="!flex !items-center !justify-center"
                >
                    <MenuUnfoldOutlined v-if="collapsed" />
                    <MenuFoldOutlined v-else />
                </AButton>

                <ADropdown placement="bottomRight">
                    <div class="flex items-center gap-2 cursor-pointer hover:opacity-80 transition-opacity">
                        <AAvatar class="!bg-blue-500">
                            <template #icon><UserOutlined /></template>
                        </AAvatar>
                        <span class="text-gray-700 font-medium hidden sm:inline">{{ user?.name }}</span>
                    </div>
                    <template #overlay>
                        <AMenu>
                            <AMenuItem key="role" disabled>
                                <span class="text-xs text-gray-400 uppercase">{{ user?.role }}</span>
                            </AMenuItem>
                            <a-menu-divider />
                            <AMenuItem key="logout" @click="handleLogout" class="!text-red-500">
                                <LogoutOutlined />
                                <span class="ml-2">Log Out</span>
                            </AMenuItem>
                        </AMenu>
                    </template>
                </ADropdown>
            </ALayoutHeader>

            <div v-if="$slots.header" class="bg-white border-b border-gray-200 px-6 py-4">
                <slot name="header" />
            </div>

            <ALayoutContent class="m-6">
                <slot />
            </ALayoutContent>
        </ALayout>
    </ALayout>
</template>
