<template>
    <table v-bind:class="classTable">
        <thead>
            <tr>
                <template v-if="headers.length !== 0">
                    <th v-for="th in headers" :key="th">
                        {{ th.toUpperCase() }}
                    </th>
                </template>
                <template v-else-if="data != null">
                    <th v-for="(value, key, index) in data[0]" :key="index">
                        {{ key.toUpperCase() }}
                    </th>
                </template>
            </tr>
        </thead>
        <tbody>            
            <tr v-for="item in data" :key="item.id" v-bind:class="classRow">
                <td v-for="(value, key, index) in item" :key="index">
                    {{ value }}
                </td>
            </tr>                
        </tbody>
    </table>
</template>

<script>
export default {
  props: {
    data: {
      type: Array,
      default: null
    },
    headers: {
      type: Array,
      default: null
    },
    tableClasses: {
      type: Array,
      default: null
    },
    headerClasses: {
      type: Array,
      default: null
    },
    rowClasses: {
      type: Array,
      default: null
    }
  },
  computed: {
    classTable: function() {
      return this.tableClasses.join(" ");
    },
    classRow: function() {
      return this.rowClasses.join(" ");
    }
  }
};
</script>

<style scoped>
</style>
