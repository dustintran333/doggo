<template>
  <div id="filters">
    <label for="offLeash">
      <input
        type="checkbox"
        v-model="offLeash"
        @change="changeFeatureOnOffLeash"
        value="Off-leash"
        name="filterFeatureOnOffLeash"
        id="offLeash">
      <img src="dog-off-leash.png" height="32">
      Off-leash
    </label>
    <label for="onLeash">
      <input
        type="checkbox"
        v-model="onLeash"
        @change="changeFeatureOnOffLeash"
        value="On-leash"
        name="filterFeatureOnOffLeash"
        id="onLeash">
      <img src="dog-on-leash.png" height="32">
      On-leash
    </label>
  </div>
</template>

<script>
export default {
  data() {
    return {
      onLeash: true,
      offLeash: true,
    };
  },
  methods: {
    changeFeatureOnOffLeash() {
      if (this.onLeash && this.offLeash) {
        this.$emit('changeFeatureOnOffLeash', '');
      } else if (this.onLeash) {
        this.$emit('changeFeatureOnOffLeash', 'On-leash');
      } else if (this.offLeash) {
        this.$emit('changeFeatureOnOffLeash', 'Off-leash');
      } else {
        this.$emit('changeFeatureOnOffLeash', 'x');
      }
    },
  },
};
</script>

<style>
  #filters {
    position: absolute;
    top: 20px;
    left: 20px;
    min-height: 40px;
    min-width: 100px;
    background-color: #eeeeee;
    padding: 10px 20px;
  }
  #filters label {
    display: block;
    height: 42px;
    line-height: 42px;
  }
  #filters label img, #filters label span {
    display: inline-block;
    width: 32px;
    height: 32px;
    vertical-align: middle;
    margin-bottom: 6px;
  }
</style>
