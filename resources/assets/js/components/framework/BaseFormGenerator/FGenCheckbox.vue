<template>
  <div class="form-group">
    <div class="form-row">
        <label class="col-sm-2 col-form-lable">{{ label }}</label>
        <div class="col">
          <div class="custom-control custom-checkbox" 
              v-for="(checkbox, index) in values" 
              :key="index"
              :class="{ 'custom-control-inline' : inline}">            
              <input class="custom-control-input" type="checkbox" 
                  :name="name" 
                  :id="name + index"
                  :value="checkbox.value" 
                  :checked="isChecked(checkbox.value)"
                  @input="update($event)">
              <label class="custom-control-label" :for="name + index">{{ checkbox.label }}</label>
          </div>
        </div>
    </div>
  </div>
</template>

<script>
export default {
  name: "FGenCheckbox",
  props: [
    "label",
    "name",
    "inline",
    "toggle",
    "description",
    "required",
    "value",
    "values"
  ],

  data() {
    return {
      selects: []
    };
  },

  methods: {
    update(event) {
      if (event.target.checked) {
        this.selects.push(event.target.value);
      } else {
        this.selects.splice(
          this.selects.findIndex(item => item == event.target.value),
          1
        );
      }
      this.$emit("input", this.selects);
    },

    isChecked(value) {
      return this.value.indexOf(value) != -1;
    }
  }
};
</script>

<style scoped>
</style>
