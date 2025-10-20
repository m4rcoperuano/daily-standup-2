<script>
  export default {
    name: 'IntegrationSettings',
    props: {
      value: {
        type: String,
        default: '',
      },
    },
    data() {
      return {
        clockworkApiToken: this.value,
      };
    },
    watch: {
      value( newVal ) {
        this.clockworkApiToken = newVal;
      },
    },
    methods: {
      emitChange() {
        this.$emit( 'input', this.clockworkApiToken );
      },
    },
  };
</script>

<template>
  <div>
    <h3>Integration Settings</h3>
    <label for="clockwork-api-token">Clockwork API Token</label>
    <input
      id="clockwork-api-token"
      v-model="clockworkApiToken"
      type="text"
      placeholder="Enter Clockwork API Token"
      autocomplete="off"
      @input="emitChange"
      />
  </div>
</template>
