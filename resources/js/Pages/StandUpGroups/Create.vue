<script setup>
  import AppLayout from '@/Layouts/AppLayout.vue';
  import InputLabel from '@/Components/InputLabel.vue';
  import InputError from '@/Components/InputError.vue';
  import TextInput from '@/Components/TextInput.vue';
  import { useForm } from '@inertiajs/vue3';
  import PrimaryButton from '@/Components/PrimaryButton.vue';
  import FormSection from '@/Components/FormSection.vue';
  import { onMounted, ref } from 'vue';
  import CustomSelect from '@/Components/CustomSelect.vue';

  defineProps( {
    hasJiraIntegration: {
      type: Boolean,
      default: false,
    },
  } );

  const boards = ref( [] );

  const form = useForm( {
    name: null,
    boardId: '',
  } );

  const submitForm = () => {
    form.post( route( 'stand-up-groups.store' ) );
  };

  const fetchBoards = async () => {
    boards.value = ( await axios.get( route( 'integrations.jira.boards' ) ) ).data;

    if ( boards.value.length === 1 ) {
      form.boardId = boards.value[0].id;
    }
  };



  onMounted( () => fetchBoards() );
</script>

<template>
  <AppLayout title="Create Stand Up Group">
    <template #header>
      <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
        New Stand Up Group
      </h2>
    </template>

    <div class="py-12 dark:text-white">
      <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <FormSection @submitted="submitForm">
          <template #title>
            Create Stand Up Group
          </template>
          <template #form>
            <div class="col-span-6 sm:col-span-4 flex flex-col gap-4">
              <div>
                <InputLabel
                  for="name"
                  value="Name"
                  ></InputLabel>
                <TextInput
                  id="name"
                  v-model="form.name"
                  type="text"
                  class="mt-1 block w-full"
                  required
                  autocomplete="name"
                  ></TextInput>
                <InputError
                  :message="form.errors.name"
                  class="mt-2"
                  ></InputError>
              </div>
              <div v-if="hasJiraIntegration">
                <InputLabel
                  for="jira-board"
                  value="Jira Integration Board"
                  ></InputLabel>
                <CustomSelect
                  v-model="form.boardId"
                  :options="boards"
                  ></CustomSelect>
              </div>
            </div>
          </template>

          <template #actions>
            <PrimaryButton
              :class="{ 'opacity-25': form.processing }"
              :disabled="form.processing"
              >
              Save
            </PrimaryButton>
          </template>
        </FormSection>
      </div>
    </div>
  </AppLayout>
</template>
