<template>  
  <input 
    ref="autoComplete" 
    class="" 
    type="text">  
</template>

<script>
export default {
  name: "GooglePlaces",

  props: {
    value: {
      type: Object,
      default: () => {
        return {};
      }
    },
    options: {
      type: Object,
      default: () => {
        return { types: ["regions"] };
      }
    }
  },

  data() {
    return {
      autoComplete: {}
    };
  },

  created() {
    
  },

  mounted() {
    this.autoComplete = new google.maps.places.Autocomplete(
      this.$refs.autoComplete,
      this.options
    );
    this.autoComplete.addListener('place_changed', this.onPlaceChange);
  },

  methods: {
    onPlaceChange(){
      console.log('change...');
      console.log(this.autoComplete.getPlace());
    }
  }
};
</script>

<style scoped>
</style>

