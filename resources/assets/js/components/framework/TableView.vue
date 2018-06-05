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
            <template v-if="loading">                
                <div class="table-loading">
                    <div class="icon" data-role="activity" data-type="square" data-style="color"></div>
                    <div class="text-light">Loading, Please Wait...</div>
                </div>               
            </template>
            <template v-else>
                <tr v-for="item in data" :key="item.id" v-bind:class="classRow">
                    <td v-for="(value, key, index) in item" :key="index">
                        {{ value }}
                    </td>
                </tr>
            </template>            
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
    },
    loading: {
      type: Boolean,
      default: true
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
.table-loading {
  top: 40%;
  left: 40%;
  width: 20%;
  padding: 10px; 
  text-align: center;
  position: absolute;
  background-color: aliceblue;
}

.table-loading .icon {
  margin-left: auto;
  margin-right: auto;
}
</style>
