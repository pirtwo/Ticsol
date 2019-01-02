<template>
  <base-table 
    :value="value"
    @input="(data)=>{ $emit('input', data) }"
    @select="(selects)=>{ $emit('select', selects) }">

    <table
      class="table table-sm table-hover table-light" 
      slot-scope="{ toggleRow, isSelected, deleteRow }">
     
      <thead> 
        <tr v-if="hasToolbar"> 
          <th :colspan="columns.length + 1">
            <slot name="toolbar" />       
          </th>    
        </tr>
        <tr>
          <th>
            <button 
              type="button"
              @click="$emit('insert')">ADD NEW</button>   
          </th>
          <th 
            v-for="column in columns" 
            :key="column.key">
            {{ column.value }}
          </th>   
        </tr>        
      </thead>
      <tbody> 
        <tr v-show="value.length < 1">
          <td :colspan="columns.length + 1">No Record</td>
        </tr>       
        <tr 
          v-for="row in value" 
          :key="row.id">
          <td>
            <button 
              type="button"
              @click="$emit('edit', row)">Edite</button>
            <button 
              type="button"
              @click="deleteRow(row)">Delete</button>
          </td>
          <slot :item="row" />
        </tr>
      </tbody>
    </table>

  </base-table>
</template>

<script>
import BaseTable from "./core/BaseTable.vue";
export default {
  name: "TsGrid",

  components: {
    "base-table": BaseTable
  },

  props: {
    value:{
      type: Array,
      default: () => {return []}
    },

    columns:{
      type:Array,
      default: () => {return []}
    },

    hasToolbar:{
      type: Boolean,
      default: false
    }
  },

  methods: {}
};
</script>

<style scoped>
</style>
