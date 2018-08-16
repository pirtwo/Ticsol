<template>
    <div class="form-group">
    <div class="form-row">
        <label class="col-sm-2 col-form-lable">{{ label }}</label>
        <div class="col">        
            <div class="custom-control custom-radio" 
            v-for="(radio, index) in values" 
            :key="index"
            :class="{ 'custom-control-inline' : inline }">            
                <input class="custom-control-input" type="radio" 
                :name="name" 
                :id="name + index"
                :checked="radio.value == value"
                :value="radio.value"            
                @input="$emit('input', $event.target.value)">
                <label class="custom-control-label" :for="name + index">{{ radio.label }}</label>
            </div>
        </div>
    </div>
    </div>
</template>

<script>
export default {
    name:"FGenRadio",
    props: [
    "label",
    "name",
    "inline",
    "description",
    "required",
    "value",
    "values"
  ]
}
</script>

<style>

</style>
