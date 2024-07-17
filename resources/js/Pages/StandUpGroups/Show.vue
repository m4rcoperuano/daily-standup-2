<script setup>
import AppLayout from "@/Layouts/AppLayout.vue";
import EditStandUpEntry from "@/Pages/StandUps/Partials/EditStandUpEntry.vue";
import {computed, onMounted, ref} from "vue";
import {DateTime} from "luxon";

const props = defineProps({
    standUpGroup: Object,
});

const standUpEntries = ref([]);

const fetchEntries = async () => {
    const response = await axios.get( route('stand-up-entries.index', props.standUpGroup.id ));
    standUpEntries.value = response.data.data;
};

const standUpEntriesGroupedByDate = computed(() => {
    return standUpEntries.value.reduce((acc, entry) => {
        const date = DateTime.fromISO(entry.date).toFormat('cccc, LLLL d');

        if (!acc[date]) {
            acc[date] = [];
        }

        acc[date].push(entry);

        return acc;
    }, {});
});

onMounted(() => {
    fetchEntries();
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
                <div class="mb-4">
                    <EditStandUpEntry :stand-up-group-id="standUpGroup.id" @saved="fetchEntries"/>
                </div>

                <div v-for="date in Object.keys(standUpEntriesGroupedByDate)" :key="date" class="p-4 bg-white dark:bg-gray-800 rounded-lg">
                    <div class="font-bold py-2 capitalize text-center text-lg">
                        {{ date }}
                    </div>
                    <table class="table w-full text-left table-collapse border dark:border-gray-950">
                        <thead>
                        <tr>
                            <th style="width:150px;">
                                <div>
                                    Team Member
                                </div>
                            </th>
                            <th>
                                <div>
                                    In Progress
                                </div>
                            </th>
                            <th>
                                <div>
                                    Priorities
                                </div>
                            </th>
                            <th>
                                <div>
                                    Blockers
                                </div>
                            </th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr v-for="entry in standUpEntriesGroupedByDate[date]" :key="entry.id">
                            <td>{{ entry.user.name }}</td>
                            <td>{{ entry.in_progress }}</td>
                            <td>{{ entry.priorities }}</td>
                            <td>{{ entry.blockers }}</td>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </AppLayout>
</template>

<style scoped>
.table {
    @apply bg-white dark:bg-gray-900;
}

.table th {
    @apply z-20 sticky top-0 font-semibold uppercase text-sm text-gray-700 bg-gray-100 border-x dark:bg-gray-950 dark:text-gray-400 dark:border-gray-700;
}

.table th div {
    @apply py-2 px-4 border-b border-gray-300 dark:border-gray-700;
}

.table tr td {
    @apply border px-4 py-2 dark:border-gray-700;
}

.table tr:nth-child(even) {
    @apply bg-gray-50 dark:bg-gray-800;
}
</style>
