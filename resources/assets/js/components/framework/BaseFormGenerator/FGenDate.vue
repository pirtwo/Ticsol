<template>
    <div class="form-group">
    <div class="form-row">
        <label class="col-sm-2 col-form-label" :for="name">{{ label }}</label>
        <div class="col">
            <input class="form-control" type="date"
                :name="name"
                :value="value"        
                :title="description"
                :required="required"
                :placeholder="placeholder"
                @input="$emit('input', $event.target.value)">
        </div>
    </div>
    </div>
</template>

<script>
export default {
    name: 'FGenDate',
    props: [
    "label",
    "name",
    "placeholder",
    "description",
    "required",
    "value"
  ]
}
</script>

<style scoped>

</style>
