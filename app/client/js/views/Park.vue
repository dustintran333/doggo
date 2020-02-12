<template>
  <div class="park" v-if="park">
    <a href="/" @click.exact.prevent="$router.push('/')">Close</a>
    <h2>{{ park.Title }}</h2>
    <ul class="park__features">
      <li>{{ park.FeatureOnOffLeash }}</li>
    </ul>
    <p class="park__notes">{{ park.Notes }}</p>
    <p class="park__provider">Managed by <strong>{{ park.Provider }}</strong></p>
  </div>
</template>

<script>
export default {
  computed: {
    park() {
      return this.$store.state.parks.find(park => park.ID === parseInt(this.$route.params.id, 10));
    },
    parks() {
      return this.$store.state.parks;
    },
  },
};
</script>

<style>
  .park {
    padding: 20px;
  }
</style>
