<template>
  <base-grid
    :value="value"
    @input="(data)=>{ $emit('input', data) }"
    @inserted="inserted"
    @updated="updated"
    @deleted="deleted"
    @select="(selects)=>{ $emit('select', selects) }"
  >
    <div
      class="table-responsive"
      slot-scope="{ selectRow, insertRow, updateRow, deleteRow, copyRow }"
    >
      <table class="table table-sm table-hover table-light">
        <thead>
          <tr v-if="hasToolbar">
            <th :colspan="columns.length + 1">
              <slot name="toolbar"/>
            </th>
          </tr>
          <tr>
            <th>
              <button 
                class="btn btn-sm" 
                type="button" 
                @click="onAdd">
                <vb-icon :icon="'playlist_add'"/>
              </button>
            </th>
            <th 
              v-for="column in columns" 
              :key="column.key">{{ column.value }}</th>
          </tr>
        </thead>
        <tbody>
          <tr v-show="value.length < 1">
            <td :colspan="columns.length + 1">No Record</td>
          </tr>
          <tr 
            v-for="(row, index) in value" 
            :key="row.id">
            <td>
              <button 
                class="btn btn-sm" 
                type="button" 
                @click="onEdit(copyRow(row), index)">
                <vb-icon :icon="'edit'"/>
              </button>
              <button 
                class="btn btn-sm" 
                type="button" 
                @click="deleteRow(row)">
                <vb-icon :icon="'delete'"/>
              </button>
            </td>
            <slot :item="row"/>
          </tr>
        </tbody>

        <ts-modal 
          :show.sync="modal" 
          :title="modalTitle">
          <slot 
            name="grid-modal" 
            :item="currentRow"/>
          <template slot="footer">
            <button
              class="btn"
              type="button"
              v-show="mode === 'insert'"
              @click="insertRow(currentRow)"
            >Insert</button>
            <button
              class="btn"
              type="button"
              v-show="mode === 'update'"
              @click="updateRow(currentRow, rowIndex)"
            >Save</button>
            <button 
              class="btn" 
              type="button" 
              @click="hideModal">Cancel</button>
          </template>
        </ts-modal>
      </table>
    </div>
  </base-grid>
</template>

<script>
import BaseGrid from "./core/BaseGrid.vue";
import TsModalVue from "./TsModal.vue";

export default {
  name: "TsGrid",

  components: {
    "base-grid": BaseGrid,
    "ts-modal": TsModalVue
  },

  props: {
    value: {
      type: Array,
      default: () => {
        return [];
      }
    },

    columns: {
      type: Array,
      default: () => {
        return [];
      }
    },

    hasToolbar: {
      type: Boolean,
      default: false
    }
  },

  data() {
    return {
      mode: "insert",
      modal: false,
      modalTitle: "",
      rowIndex: 0,
      currentRow: {}
    };
  },

  methods: {
    inserted(row) {
      this.hideModal();
      this.$emit("inserted", row);
    },

    updated(row) {
      this.hideModal();
      this.$emit("updated", row);
    },

    deleted(row) {
      this.$emit("deleted", row);
    },

    onAdd() {
      this.mode = "insert";
      this.currentRow = {};
      this.modalTitle = "Add New Row";
      this.showModal();
    },

    onEdit(row, index) {
      this.mode = "update";
      this.currentRow = row;
      this.rowIndex = index;
      this.modalTitle = "Update Row";
      this.showModal();
    },

    showModal() {
      this.modal = true;
      this.$emit('modalShow');
    },

    hideModal() {      
      this.modal = false;
      this.$emit('modalHide');
    }
  }
};
</script>

<style scoped>
</style>
