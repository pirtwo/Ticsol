<template>
  <input 
    ref="autoComplete" 
    v-model="inputValue" 
    class="form-control" 
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
    },
    formOptions: {
      type: Object,
      default: () => {
        return {
          street_number: "short_name",
          route: "short_name",
          locality: "short_name",
          administrative_area_level_1: "short_name",
          administrative_area_level_2: "short_name",
          administrative_area_level_3: "short_name",
          country: "long_name",
          postal_code: "short_name"
        };
      }
    }
  },

  data() {
    return {
      inputValue: "",
      autoComplete: {}
    };
  },

  watch: {
    value: function(value) {
      if (value.formatted_address) {
        this.inputValue = value.formatted_address;
      } else {
        this.inputValue = "";
      }
    }
  },

  created() {},

  mounted() {
    this.autoComplete = new google.maps.places.Autocomplete(
      this.$refs.autoComplete,
      {}
    );
    this.autoComplete.addListener("place_changed", this.onPlaceChange);
  },

  methods: {
    onPlaceChange() {
      let form = {};
      let place = this.autoComplete.getPlace();
      form.formatted_address = place.formatted_address;
      Object.keys(this.formOptions).forEach(formKey => {
        form[formKey] = "";
        place.address_components.forEach(addressPart => {
          if (addressPart.types.indexOf(formKey) > -1) {
            form[formKey] = addressPart[this.formOptions[formKey]];
          }
        });
      });
      this.$emit("input", form);
    }
  }
};
</script>

<style>
.pac-container {
  z-index: 2000;
}
</style>

