<template>
    <AuthenticatedLayout>
        <div
            class="flex-1 flex-col items-center justify-center rounded-lg border border-dashed p-6 shadow-sm"
        >
            <Tabs default-value="account">
                <TabsList class="w-[400px]">
                    <TabsTrigger value="account"> Account </TabsTrigger>
                    <TabsTrigger value="account_category">
                        Account Category
                    </TabsTrigger>
                    <TabsTrigger value="account_sub_category">
                        Account Sub Category
                    </TabsTrigger>
                </TabsList>
                <TabsContent value="account" class="my-4">
                    <AccountTable
                        :accounts="accounts"
                        :category="category"
                        :sub-category="sub_category"
                    />
                </TabsContent>
                <TabsContent value="account_category" class="my-4">
                    <AccountCategoryTable :data="category" />
                </TabsContent>
                <TabsContent value="account_sub_category" class="my-4">
                    <AccountSubCategoryTable :data="sub_category" />
                </TabsContent>
            </Tabs>
        </div>
    </AuthenticatedLayout>
</template>

<script setup>
import AuthenticatedLayout from '@/layouts/AuthenticatedLayout.vue';
import { Tabs, TabsContent, TabsList, TabsTrigger } from '@/components/ui/tabs';
import AccountTable from './components/TableAccount.vue';
import AccountCategoryTable from './components/TableAccountCategory.vue';
import AccountSubCategoryTable from './components/TableAccountSubCategory.vue';
import { router } from '@inertiajs/vue3';
import { ref } from 'vue';

const props = defineProps({
    accounts: Object,
    category: Object,
    sub_category: Object,
});
</script>
