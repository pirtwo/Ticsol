<template>
  <div class="form-group">
    <div class="form-row">
      <label 
        class="col-sm-2 col-form-lable" 
        :for="name"
      >{{ label }}</label>
      <div class="col">
        <input 
          class="form-control"
          :type="subtype" 
          :name="name" 
          :value="value"   
          :title="description"         
          :required="required"
          :maxlength="maxlength"
          :placeholder="placeholder"
          @input="$emit('input', $event.target.value)"
        >
      </div>
    </div>
  </div>  
</template>

<script>
export default {
  name: "FGenTextbox",
  props: [
    "label",
    "name",
    "subtype",
    "placeholder",
    "description",
    "required",
    "value",
    "maxlength"
  ]
};
</script>

<style scoped>
</style>
