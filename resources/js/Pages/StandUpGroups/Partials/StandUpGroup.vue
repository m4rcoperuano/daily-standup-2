<script setup>
  import VueMarkdown from 'vue-markdown-render';


  defineProps( {
    title: String,
    standUpEntries: Array,
  } );
</script>

<template>
  <div class="font-bold py-2 capitalize text-center text-lg">
    {{ title }}
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
      <tr
        v-for="entry in standUpEntries"
        :key="entry.id"
        >
        <td>{{ entry.user.name }}</td>
        <td>
          <VueMarkdown
            v-if="entry.in_progress"
            :source="entry.in_progress"
            ></VueMarkdown>
        </td>
        <td>
          <VueMarkdown
            v-if="entry.priorities"
            :source="entry.priorities"
            ></VueMarkdown>
        </td>
        <td>
          <VueMarkdown
            v-if="entry.blockers"
            :source="entry.blockers"
            ></VueMarkdown>
        </td>
      </tr>
    </tbody>
  </table>
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
    @apply align-top;
    @apply border px-4 py-2 dark:border-gray-700 prose prose-ul:m-0 prose-p:m-0 prose-h1:m-0 prose-h2:m-0 prose-h3:m-0 prose-h4:m-0 prose-h5:m-0 prose-h6:m-0 prose-li:m-0 prose-h1:text-lg prose-h2:text-lg prose-h3:text-lg;
}

.table tr:nth-child(even) {
    @apply bg-gray-50 dark:bg-gray-800;
}
</style>
