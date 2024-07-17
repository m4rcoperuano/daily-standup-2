<script setup>
import AppLayout from "@/Layouts/AppLayout.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import EditStandUpEntry from "@/Pages/StandUps/Partials/EditStandUpEntry.vue";

defineProps({
    standUpGroup: Object,
});
</script>

<template>
    <AppLayout title="Stand Up">
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                Stand Up {{ standUpGroup.name }}
            </h2>
        </template>
        <div class="py-12 dark:text-white">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <EditStandUpEntry/>
            </div>
        </div>
    </AppLayout>
</template>

<style scoped>

</style>
