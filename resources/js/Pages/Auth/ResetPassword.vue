<script setup>
  import { Head, useForm } from '@inertiajs/vue3';
  import AuthenticationCard from '@/Components/AuthenticationCard.vue';
  import AuthenticationCardLogo from '@/Components/AuthenticationCardLogo.vue';
  import InputError from '@/Components/InputError.vue';
  import InputLabel from '@/Components/InputLabel.vue';
  import PrimaryButton from '@/Components/PrimaryButton.vue';
  import TextInput from '@/Components/TextInput.vue';

  const props = defineProps( {
    email: String,
    token: String,
  } );

  const form = useForm( {
    token: props.token,
    email: props.email,
    password: '',
    password_confirmation: '',
  } );

  const submit = () => {
    form.post( route( 'password.update' ), {
      onFinish: () => form.reset( 'password', 'password_confirmation' ),
    } );
  };
</script>

<template>
  <Head title="Reset Password"></Head>

  <AuthenticationCard>
    <template #logo>
      <AuthenticationCardLogo></AuthenticationCardLogo>
    </template>

    <form @submit.prevent="submit">
      <div>
        <InputLabel
          for="email"
          value="Email"
          ></InputLabel>
        <TextInput
          id="email"
          v-model="form.email"
          type="email"
          class="mt-1 block w-full"
          required
          autofocus
          autocomplete="username"
          ></TextInput>
        <InputError
          class="mt-2"
          :message="form.errors.email"
          ></InputError>
      </div>

      <div class="mt-4">
        <InputLabel
          for="password"
          value="Password"
          ></InputLabel>
        <TextInput
          id="password"
          v-model="form.password"
          type="password"
          class="mt-1 block w-full"
          required
          autocomplete="new-password"
          ></TextInput>
        <InputError
          class="mt-2"
          :message="form.errors.password"
          ></InputError>
      </div>

      <div class="mt-4">
        <InputLabel
          for="password_confirmation"
          value="Confirm Password"
          ></InputLabel>
        <TextInput
          id="password_confirmation"
          v-model="form.password_confirmation"
          type="password"
          class="mt-1 block w-full"
          required
          autocomplete="new-password"
          ></TextInput>
        <InputError
          class="mt-2"
          :message="form.errors.password_confirmation"
          ></InputError>
      </div>

      <div class="flex items-center justify-end mt-4">
        <PrimaryButton
          :class="{ 'opacity-25': form.processing }"
          :disabled="form.processing"
          >
          Reset Password
        </PrimaryButton>
      </div>
    </form>
  </AuthenticationCard>
</template>
