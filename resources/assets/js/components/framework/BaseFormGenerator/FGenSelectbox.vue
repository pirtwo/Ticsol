<template>  
  <div class="form-group">
    <div class="form-row">
      <label 
        class="col-sm-2 col-form-label" 
        :for="name">{{ label }}</label>
      <div class="col">
        <select 
          class="form-control" 
          :name="name"
          :value="value"
          :title="description"
          :required="required"             
          :multiple="multiple"
          @input="$emit('input', $event.target.value)">
          <option 
            disabled 
            value="" 
            selected="true">{{ placeholder }}</option>
          <option 
            v-for="(option, index) in values" 
            :key="index" 
            :value="option.value">
            {{ option.label }}
          </option>
        </select>
      </div>
    </div> 
  </div>  
</template>

<script>
export default {
  name: "FGenSelectbox",
  props: [
    "label",
    "name",
    "placeholder",
    "description",
    "required",
    "value",
    "values",
    "multiple"
  ]
};
</script>

<style scoped>
</style>
