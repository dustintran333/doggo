<template>
  <div class="home">

  </div>
</template>

<script>
export default {
  name: 'home',
  components: {

  },
  data() {
    return {

    };
  },
  computed: {
    parks() {
      return this.$store.state.parks;
    },
  },
};
</script>

<style>

</style>
