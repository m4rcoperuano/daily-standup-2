<script setup>
  import { Head, Link, useForm } from '@inertiajs/vue3';
  import AuthenticationCard from '@/Components/AuthenticationCard.vue';
  import AuthenticationCardLogo from '@/Components/AuthenticationCardLogo.vue';
  import Checkbox from '@/Components/Checkbox.vue';
  import InputError from '@/Components/InputError.vue';
  import InputLabel from '@/Components/InputLabel.vue';
  import PrimaryButton from '@/Components/PrimaryButton.vue';
  import TextInput from '@/Components/TextInput.vue';

  const form = useForm( {
    name: '',
    email: '',
    password: '',
    password_confirmation: '',
    terms: false,
  } );

  const submit = () => {
    form.post( route( 'register' ), {
      onFinish: () => form.reset( 'password', 'password_confirmation' ),
    } );
  };
</script>

<template>
  <Head title="Register"></Head>

  <AuthenticationCard>
    <template #logo>
      <AuthenticationCardLogo></AuthenticationCardLogo>
    </template>

    <form @submit.prevent="submit">
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
          autofocus
          autocomplete="name"
          ></TextInput>
        <InputError
          class="mt-2"
          :message="form.errors.name"
          ></InputError>
      </div>

      <div class="mt-4">
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

      <div
        v-if="$page.props.jetstream.hasTermsAndPrivacyPolicyFeature"
        class="mt-4"
        >
        <InputLabel for="terms">
          <div class="flex items-center">
            <Checkbox
              id="terms"
              v-model:checked="form.terms"
              name="terms"
              required
              ></Checkbox>

            <div class="ms-2">
              I agree to the <a
                target="_blank"
                :href="route('terms.show')"
                class="underline text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-800"
                >Terms of Service</a> and <a
                target="_blank"
                :href="route('policy.show')"
                class="underline text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-800"
                >Privacy Policy</a>
            </div>
          </div>
          <InputError
            class="mt-2"
            :message="form.errors.terms"
            ></InputError>
        </InputLabel>
      </div>

      <div class="flex items-center justify-end mt-4">
        <Link
          :href="route('login')"
          class="underline text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-800"
          >
          Already registered?
        </Link>

        <PrimaryButton
          class="ms-4"
          :class="{ 'opacity-25': form.processing }"
          :disabled="form.processing"
          >
          Register
        </PrimaryButton>
      </div>
    </form>
  </AuthenticationCard>
</template>
