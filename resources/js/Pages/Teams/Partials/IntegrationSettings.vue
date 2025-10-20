<script setup>
  import SectionBorder from '@/Components/SectionBorder.vue';
  import FormSection from '@/Components/FormSection.vue';
  import TextInput from '@/Components/TextInput.vue';
  import InputLabel from '@/Components/InputLabel.vue';
  import { useForm } from '@inertiajs/vue3';
  import PrimaryButton from '@/Components/PrimaryButton.vue';
  import ActionMessage from '@/Components/ActionMessage.vue';
  import { useApi } from '@/useApi';
  import { onMounted } from 'vue';
  import SecondaryButton from '@/Components/SecondaryButton.vue';

  const api = useApi();
  const form = useForm( {
    clockwork: null,
    unlock_key: false,
  } );

  const save = async () => {
    await api.teamSettings.integrations.save( form.clockwork );
    form.recentlySuccessful = true;
    form.unlock_key = false;

    const response = await api.teamSettings.integrations.get();
    form.clockwork = response.result.data.clockwork_api_key;
  };

  onMounted( async () => {
    const response = await api.teamSettings.integrations.get();
    form.clockwork = response.result.data.clockwork_api_key;
  } );
</script>

<template>
  <div>
    <div>
      <SectionBorder></SectionBorder>
      <FormSection @submitted="save">
        <template #title>
          Integration Settings
        </template>
        <template #description>
          Configure the integration settings for your team.
        </template>
        <template #form>
          <div class="col-span-6 sm:col-span-4">
            <InputLabel
              for="clockwork"
              value="Clockwork Integration"
              ></InputLabel>
            <TextInput
              id="clockwork"
              v-model="form.clockwork"
              type="password"
              :disabled="!form.unlock_key"
              class="mt-1 block w-full disabled:opacity-50"
              ></TextInput>
            <SecondaryButton
              v-if="!form.unlock_key"
              class="mt-2"
              @click="form.clockwork = null; form.unlock_key = true"
              >
              Change Key
            </SecondaryButton>
          </div>
        </template>
        <template #actions>
          <ActionMessage
            :on="form.recentlySuccessful"
            class="me-3"
            >
            Saved.
          </ActionMessage>
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
</template>
