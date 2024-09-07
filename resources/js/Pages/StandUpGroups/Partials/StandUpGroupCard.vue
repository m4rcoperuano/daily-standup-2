<script setup lang="ts">
  import SecondaryButton from '@/Components/SecondaryButton.vue';
  import PrimaryButton from '@/Components/PrimaryButton.vue';

  defineProps( {
    title: {
      type: String,
      required: true,

    },
    description: {
      type: String,
      required: false,
      default: null,
    },
    groupId: {
      type: Number,
      required: true,
    },
    canEdit: {
      type: Boolean,
      required: false,
    },
  } );
</script>

<template>
  <div class="bg-white border border-gray-200 rounded-lg shadow dark:bg-gray-800 dark:border-gray-700 flex">
    <!--    <a href="#">-->
    <!--      <img-->
    <!--        class="rounded-t-lg"-->
    <!--        src="/docs/images/blog/image-1.jpg"-->
    <!--        alt=""-->
    <!--        />-->
    <!--    </a>-->
    <div class="p-5 flex-grow">
      <a href="#">
        <h5 class="mb-2 text-2xl font-bold tracking-tight text-gray-900 dark:text-white">
          {{ title }}
        </h5>
      </a>
      <p
        v-if="description"
        class="mb-3 font-normal text-gray-700 dark:text-gray-400"
        >
        {{ description }}
      </p>
      <div class="gap-4 flex">
        <PrimaryButton
          link
          :route="route('stand-up-groups.show', groupId)"
          >
          Go
          <svg
            class="rtl:rotate-180 w-3.5 h-3.5 ms-2"
            aria-hidden="true"
            xmlns="http://www.w3.org/2000/svg"
            fill="none"
            viewBox="0 0 14 10"
            >
            <path
              stroke="currentColor"
              stroke-linecap="round"
              stroke-linejoin="round"
              stroke-width="2"
              d="M1 5h12m0 0L9 1m4 4L9 9"
              ></path>
          </svg>
        </PrimaryButton>
        <SecondaryButton
          v-if="canEdit"
          link
          :route="route('stand-up-groups.edit', groupId)"
          >
          Edit
          <svg
            xmlns="http://www.w3.org/2000/svg"
            fill="none"
            viewBox="0 0 24 24"
            stroke-width="1.5"
            stroke="currentColor"
            class="w-3.5 h-3.5 ms-2"
            >
            <path
              stroke-linecap="round"
              stroke-linejoin="round"
              d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0 1 15.75 21H5.25A2.25 2.25 0 0 1 3 18.75V8.25A2.25 2.25 0 0 1 5.25 6H10"
              ></path>
          </svg>
        </SecondaryButton>
      </div>
    </div>
  </div>

</template>

<style scoped>

</style>
