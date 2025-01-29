<template>
    <AuthenticatedLayout>
        <div class="flex items-center">
            <h1 class="text-lg font-semibold md:text-2xl">Inventory</h1>
        </div>
        <div
            class="flex-1 flex-col items-center justify-center rounded-lg border border-dashed p-6 shadow-sm"
        >
            <Button class="mb-4" @click="toCreate()">Tambah Member</Button>
            <MemberTable
                :members="members"
                @edit="editMember"
                @delete="deleteMember"
            />
        </div>
    </AuthenticatedLayout>
</template>

<script setup>
import MemberTable from '@/components/MemberTable.vue';
import AuthenticatedLayout from '@/layouts/AuthenticatedLayout.vue';
import { router } from '@inertiajs/vue3';
import { Button } from '@/components/ui/button';

defineProps({
    members: Object,
});

function toCreate() {
    router.get('members/create');
}

function editMember(member) {
    route('members.edit', member.id);
}
function deleteMember(member) {
    if (confirm('Are you sure you want to delete this member?')) {
        route('members.destroy', member.id);
    }
}
</script>
