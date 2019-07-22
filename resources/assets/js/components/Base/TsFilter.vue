<template>
  <div
    class="modal fade"
    id="filterModal"
    tabindex="-1"
    role="dialog"
    aria-labelledby="filter"
    aria-hidden="true"
  >
    <div
      class="modal-dialog modal-dialog-centered"
      role="document"
    >
      <div class="modal-content">
        <div class="modal-header">
          <h5
            class="modal-title"
            id="filter"
          >
            <ts-icon
              icon="filter_list"
              style="vertical-align: text-bottom;"
            />Filter
          </h5>
          <button
            type="button"
            class="close"
            data-dismiss="modal"
            aria-label="Close"
          >
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <div class="form-group">
            <div class="form-row">
              <div class="col-sm-6">
                <select
                  v-model="col"
                  class="custom-select"
                  @change="opt = ''"
                >
                  <option
                    disabled
                    value
                  >
                    Select Column
                  </option>
                  <option
                    v-for="item in columns"
                    :key="item.key"
                    :value="item.key"
                  >
                    {{ item.value }}
                  </option>
                </select>
              </div>
              <div class="col-sm-6">
                <select
                  v-model="opt"
                  class="custom-select"
                >
                  <option
                    disabled
                    value
                  >
                    Select Operator
                  </option>
                  <option
                    v-for="item in operators"
                    :key="item.key"
                    :value="item.key"
                    v-show="showOperator(item)"
                  >
                    {{ item.value }}
                  </option>
                </select>
              </div>
            </div>
          </div>
          <div class="form-group">
            <div class="form-row">
              <div class="col-sm-12">
                <div class="input-group">
                  <input
                    v-model="val"
                    :placeholder="valuePlaceholder"
                    class="form-control"
                    type="text"
                  >
                  <div class="input-group-append">
                    <button
                      type="button"
                      class="btn btn-success"
                      title="Add filter to list"
                      @click="addFilter"
                    >
                      Add to list
                    </button>
                  </div>
                </div>
              </div>
            </div>
          </div> 

          <div>
            <i class="text-muted">
              {{ `${this.filters.length > 0 ? this.filters.length : "No"} filter added.` }}
              <button
                type="button"
                class="btn btn-link"
                @click="showFilters = !showFilters"
              >
                <i>{{ this.showFilters ? "hide filters" : "show filters" }}</i>                
              </button>
              <button
                type="button"
                class="btn btn-link"
                @click="clearAll"
              >
                <i>clear all</i>
              </button>
            </i>
          </div>        

          <div
            v-show="showFilters"
            class="table-responsive"
          >
            <table class="table table-sm table-dark">
              <thead>
                <tr>
                  <th scope="col">
                    Column
                  </th>
                  <th scope="col">
                    Operator
                  </th>
                  <th scope="col">
                    Value
                  </th>
                  <th />
                </tr>
              </thead>
              <tbody>
                <tr
                  colspan="4"
                  v-show="filters.length < 1"
                >
                  No Filter
                </tr>
                <tr
                  v-for="(query, index) in filters"
                  :key="index"
                >
                  <td>{{ getColumnName(query.col) }}</td>
                  <td>{{ getOperatorName(query.opt) }}</td>
                  <td>{{ query.val }}</td>
                  <td>
                    <button
                      type="button"
                      class="btn btn-link"
                      @click="removeFilter(index)"
                    >
                      Remove
                    </button>
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
        <div class="modal-footer">
          <button
            type="button"
            class="btn btn-light"
            data-dismiss="modal"
            @click="clearForm"
          >
            Cancel
          </button>          
          <button
            type="button"
            class="btn btn-primary"
            @click="applyFilter"
          >
            Apply
          </button>   
        </div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  name: "TsFilter",

  props: {
    value: {
      type: Array,
      default: null
    },
    show: {
      type: Boolean,
      default: false
    },
    columns: {
      type: Array,
      default: null
    }
  },

  data() {
    return {
      col: "",
      opt: "",
      val: "",
      filters: [],
      showFilters: false,
      operators: [
        { key: "cnt", value: "Contains", type: ["string"] },
        { key: "ncnt", value: "Not Contains", type: ["string"] },
        { key: "eq", value: "Equals", type: ["number", "string", "boolean"] },
        {
          key: "neq",
          value: "Not Equals",
          type: ["number", "string", "boolean"]
        },
        { key: "gt", value: "Greater Than", type: ["number", "date"] },
        { key: "lt", value: "Less Than", type: ["number", "date"] },
        {
          key: "gte",
          value: "Greater Than Or Equal",
          type: ["number", "date"]
        },
        { key: "lte", value: "Less Than Or Equal", type: ["number", "date"] },
        { key: "btw", value: "Between", type: ["number", "date"] },
        { key: "nbtw", value: "Not Btween", type: ["number", "date"] },
        { key: "in", value: "In", type: ["number", "string", "date"] },
        // { key: "orderby", value: "Orderby", type: ["boolean", "number", "string", "date"] }
      ]
    };
  },

  watch: {    
    show: function(value) {
      if (value) this.showModal();
      else this.hideModal();
    }
  },

  computed: {
    valuePlaceholder: function() {      
      if(this.opt == "orderby") return "Ase or Des...";
      if (!this.col) return "";
      return this.columns.find(item => item.key == this.col).placeholder;
    }
  },

  created() {},

  mounted() {
    $("#filterModal").on("show.bs.modal", this.onShow);
    $("#filterModal").on("hide.bs.modal", this.onHide);
  },

  destroyed() {},

  methods: {
    showOperator(operator) {
      if (!this.col) return true;
      let col = this.columns.find(item => item.key == this.col);      
      return operator.type.indexOf(col.type) > -1;
    },

    showModal() {
      $("#filterModal").modal("show");
    },

    hideModal() {
      $("#filterModal").modal("hide");
    },

    onShow() {
      this.$emit("update:show", true);
    },

    onHide() {
      this.$emit("update:show", false);
    },

    getColumnName(key) {
      return this.columns.find(item => item.key === key).value;
    },

    getOperatorName(key) {
      return this.operators.find(item => item.key === key).value;
    },

    addFilter() {
      if (this.col === "" || this.opt === "" || this.val === "") return;
      this.filters.push({
        opt: this.opt,
        col: this.col,
        val: this.val
      });
    },

    removeFilter(index) {
      this.filters.splice(index, 1);
    },

    applyFilter() {
      this.$emit("input", [...this.filters]);
      this.$emit("apply");
      this.hideModal();
    },

    clearForm(){      
      this.col = "";
      this.opt = "";
      this.val = "";
    },

    clearAll(){
      this.clearForm();
      this.filters = [];
    }
  }
};
</script>

<style scoped>
.baseFilter__select-column {
  font-size: 15px;
  max-width: 100px;
}

.baseFilter__select-operator {
  font-size: 15px;
  max-width: 100px;
}

.baseFilter__value-input {
  font-size: 15px;
  max-width: 150px;
}
</style>