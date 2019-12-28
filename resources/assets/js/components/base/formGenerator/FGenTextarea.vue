<template>    
  <div class="form-group">
    <div class="form-row">
      <label 
        class="col-sm-2 col-form-label" 
        :for="name"
      >{{ label ? label : name }}</label>
      <div class="col">
        <textarea 
          class="form-control"
          :name="name"
          :rows="rows"
          :value="value"
          :title="description"
          :required="required"
          :maxlength="maxlength"
          :placeholder="placeholder" 
          @input="$emit('input', $event.target.value)"
        />
      </div>        
    </div>   
  </div> 
</template>

<script>
export default {
  name: "FGenTextarea",
  props: [
    "label",
    "name",
    "placeholder",
    "description",
    "required",
    "value",
    "maxlength",
    "rows"
  ]
};
</script>

<style scoped>
</style>
