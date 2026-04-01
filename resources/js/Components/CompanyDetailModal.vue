<script setup>
import { LinkOutlined } from '@ant-design/icons-vue';
import {
    Descriptions as ADescriptions,
    DescriptionsItem as ADescriptionsItem,
    Image as AImage,
    Modal as AModal,
    Tag as ATag
} from 'ant-design-vue';

defineProps({
    open: Boolean,
    company: {
        type: Object,
        default: null,
    },
});

const emit = defineEmits(['close']);
</script>

<template>
    <AModal
        :open="open"
        title="Company Details"
        @cancel="emit('close')"
        :footer="null"
        width="520px"
    >
        <div v-if="company" class="py-4">
            <div v-if="company.logo" class="flex justify-center mb-6">
                <AImage
                    :src="company.logo"
                    :width="120"
                    :height="120"
                    class="rounded-lg object-cover"
                />
            </div>

            <ADescriptions bordered :column="1" size="middle">
                <ADescriptionsItem label="Name">
                    <span class="font-medium">{{ company.name }}</span>
                </ADescriptionsItem>
                <ADescriptionsItem label="Email">
                    <span v-if="company.email">{{ company.email }}</span>
                    <ATag v-else color="default">Not provided</ATag>
                </ADescriptionsItem>
                <ADescriptionsItem label="Website">
                    <a
                        v-if="company.website"
                        :href="company.website"
                        target="_blank"
                        rel="noopener noreferrer"
                        class="text-blue-500 hover:text-blue-700 flex items-center gap-1"
                    >
                        <LinkOutlined />
                        {{ company.website }}
                    </a>
                    <ATag v-else color="default">Not provided</ATag>
                </ADescriptionsItem>
            </ADescriptions>
        </div>
    </AModal>
</template>
