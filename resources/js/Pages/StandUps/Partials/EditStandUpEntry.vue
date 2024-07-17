<script setup>

import InputLabel from "@/Components/InputLabel.vue";
import InputError from "@/Components/InputError.vue";
import TextInput from "@/Components/TextInput.vue";
</script>

<template>
    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg mt-4 p-4">
        <h1 class="text-lg mb-2">
            New Stand Up Entry
        </h1>
        <div class="col-span-6 sm:col-span-4 mb-2">
            <InputLabel for="date" value="Date" />
            <TextInput
                id="date"
                type="date"
                class="mt-1 block w-full"
                required
            />
        </div>
        <div class="grid grid-cols-3">
            <div class="bg-teal-950 px-4 py-2 rounded-tl rounded bl">
                In Progress

            </div>
            <div class="bg-blue-950 px-4 py-2">
                Priorities
            </div>
            <div class="bg-red-950 px-4 py-2 rounded-tr rounded-br">
                Blockers
            </div>
        </div>
    </div>
</template>

<style scoped>

</style>
